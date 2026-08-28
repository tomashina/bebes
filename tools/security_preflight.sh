#!/usr/bin/env bash

set -u
set -o pipefail
export LC_ALL=C

repo_root=$(cd -- "$(dirname -- "$0")/.." && pwd -P) || exit 1
status=0

known_iocs='910795a0d4711c2b4293ce0009cec2ea|4c1f0af86278a5f0387a2edca2b46ed1|db9f73de13f763d0696f3cde7b8cdf72|cae7f991ee601d64253074e6703a892f|alt\.rm-5mym5dd@yopmail\.com|anythingpro\.net/get2\.php|aHR0cDovL2FueXRoaW5ncHJvLm5ldC9nZXQyLnBocA'

if git -C "$repo_root" grep -nEI "$known_iocs" -- \
  ':!tools/security_preflight.sh' ':!README.md'; then
  printf 'ERROR: a confirmed incident IOC is present in tracked source.\n' >&2
  status=1
fi

if git -C "$repo_root" ls-files | grep -E \
  '^(config\.php|admin/config\.php|\.env([^/]*)?|\.user\.ini|php\.ini|admin/php\.ini|error_log)$|^system/storage/(cache|logs|session|upload|download|modification|marketplace)/|^vqmod/(vqcache|logs)/|^image/(cache|catalog)/|^_incident/' \
  | grep -vE '^image/catalog/\.htaccess$'; then
  printf 'ERROR: secret/runtime/generated/evidence paths are tracked.\n' >&2
  status=1
fi

if git -C "$repo_root" grep -nF 'bebes.test' -- ':!tools/security_preflight.sh'; then
  printf 'ERROR: local development URL is present in tracked source.\n' >&2
  status=1
fi

secret_material='BEGIN (RSA |EC |OPENSSH )?PRIVATE KEY|AKIA[0-9A-Z]{16}|gh[pousr]_[A-Za-z0-9]{30,}|sk_live_[A-Za-z0-9]{16,}|xox[baprs]-[A-Za-z0-9-]{20,}'

if git -C "$repo_root" grep -nEI "$secret_material" -- ':!tools/security_preflight.sh'; then
  printf 'ERROR: high-confidence secret material is present in tracked source.\n' >&2
  status=1
fi

php_failures=$(mktemp "${TMPDIR:-/tmp}/bebes-php-lint.XXXXXX") || exit 1
trap 'rm -f -- "$php_failures"' EXIT HUP INT TERM

while IFS= read -r -d '' file; do
  case "$file" in
    *.php|*.phtml)
      if ! php -l "$repo_root/$file" >/dev/null 2>&1; then
        printf '%s\n' "$file" >> "$php_failures"
      fi
      ;;
  esac
done < <(git -C "$repo_root" ls-files -z)

if [ -s "$php_failures" ]; then
  printf 'ERROR: PHP lint failed:\n' >&2
  sed -n '1,100p' "$php_failures" >&2
  status=1
fi

if [ "$status" -eq 0 ]; then
  printf 'Security preflight passed.\n'
fi

exit "$status"
