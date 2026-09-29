#!/bin/sh

# Publish the daily PJ1/PJ3 price lists at 00:30 Europe/Zagreb.
#
# The production server runs cron in UTC. Schedule this wrapper at both UTC
# candidates (22:30 and 23:30); the local-time gate below lets exactly one of
# them continue, including after daylight-saving time changes.

set -u

PATH=/usr/bin:/bin
export PATH
TZ=Europe/Zagreb
export TZ

CONFIG_FILE=/home/amds/.config/bebes-anchor-curl.conf
LOG_DIRECTORY=/home/amds/logs
LOG_FILE=$LOG_DIRECTORY/bebes-anchor-cron.log
TEMP_DIRECTORY=/home/amds/.config
RUN_NOW=0

case "${1:-}" in
	'')
		;;
	--run-now)
		RUN_NOW=1
		;;
	*)
		printf '%s\n' 'Objava cjenika nije uspjela; provjerite bebes-anchor-cron.log.' >&2
		exit 64
		;;
esac

if [ "$RUN_NOW" -eq 0 ]; then
	LOCAL_TIME=$(date '+%H:%M')
	LOCAL_WEEKDAY=$(date '+%u')

	if [ "$LOCAL_TIME" != '00:30' ] || [ "$LOCAL_WEEKDAY" -gt 5 ]; then
		exit 0
	fi
fi

umask 077

if ! mkdir -p "$LOG_DIRECTORY" "$TEMP_DIRECTORY" 2>/dev/null; then
	printf '%s\n' 'Objava cjenika nije uspjela; provjerite bebes-anchor-cron.log.' >&2
	exit 1
fi

if ! { : >> "$LOG_FILE"; } 2>/dev/null || ! chmod 600 "$LOG_FILE" 2>/dev/null; then
	printf '%s\n' 'Objava cjenika nije uspjela; provjerite bebes-anchor-cron.log.' >&2
	exit 1
fi

log_failure() {
	TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S %Z')
	{ printf '%s curl_exit=%s http_status=%s json_success=%s\n' \
		"$TIMESTAMP" "${1:-not_run}" "${2:-000}" "${3:-0}" >> "$LOG_FILE"; } 2>/dev/null
	printf '%s\n' 'Objava cjenika nije uspjela; provjerite bebes-anchor-cron.log.' >&2
	exit 1
}

if [ ! -f "$CONFIG_FILE" ] || [ ! -r "$CONFIG_FILE" ]; then
	log_failure not_run 000 0
fi

RESPONSE_FILE=$(mktemp "$TEMP_DIRECTORY/.bebes-anchor-response.XXXXXX" 2>/dev/null) || log_failure not_run 000 0

cleanup() {
	rm -f -- "$RESPONSE_FILE" 2>/dev/null
}

trap cleanup EXIT HUP INT TERM

HTTP_STATUS=$(/bin/curl \
	--config "$CONFIG_FILE" \
	--output "$RESPONSE_FILE" \
	--write-out '%{http_code}' \
	2>/dev/null)
CURL_STATUS=$?

JSON_SUCCESS=0
PHP_BIN=''

for PHP_CANDIDATE in \
	/usr/local/bin/ea-php74 \
	/opt/cpanel/ea-php74/root/usr/bin/php \
	/usr/local/bin/php \
	/usr/bin/php
do
	if [ -x "$PHP_CANDIDATE" ]; then
		PHP_BIN=$PHP_CANDIDATE
		break
	fi
done

if [ "$CURL_STATUS" -eq 0 ] && [ "$HTTP_STATUS" = '200' ] && [ -n "$PHP_BIN" ]; then
	if "$PHP_BIN" -r '
		$payload = json_decode(stream_get_contents(STDIN), true);
		if (!is_array($payload) || !isset($payload["success"]) || $payload["success"] !== true || !isset($payload["locations"]) || !is_array($payload["locations"])) {
			exit(1);
		}
		foreach (array("PJ1", "PJ3") as $location) {
			if (!isset($payload["locations"][$location]) || !is_array($payload["locations"][$location]) || empty($payload["locations"][$location]["success"]) || empty($payload["locations"][$location]["publication"]) || !is_array($payload["locations"][$location]["publication"])) {
				exit(1);
			}
		}
		exit(0);
	' < "$RESPONSE_FILE" >/dev/null 2>&1; then
		JSON_SUCCESS=1
	fi
fi

TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S %Z')
if ! { printf '%s curl_exit=%s http_status=%s json_success=%s\n' \
	"$TIMESTAMP" "$CURL_STATUS" "${HTTP_STATUS:-000}" "$JSON_SUCCESS" >> "$LOG_FILE"; } 2>/dev/null; then
	printf '%s\n' 'Objava cjenika nije uspjela; provjerite bebes-anchor-cron.log.' >&2
	exit 1
fi

if [ "$CURL_STATUS" -ne 0 ] || [ "$HTTP_STATUS" != '200' ] || [ "$JSON_SUCCESS" -ne 1 ]; then
	printf '%s\n' 'Objava cjenika nije uspjela; provjerite bebes-anchor-cron.log.' >&2
	exit 1
fi

exit 0
