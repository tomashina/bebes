#!/usr/bin/env bash

# Builds a clean replacement webroot while the current production .htaccess
# still returns 403. The old webroot is moved intact into a recoverable
# quarantine. The reviewed production .htaccess is prepared but never enabled.

set -eu
set -o pipefail
export LC_ALL=C
export TZ=UTC
umask 077

if [ "$#" -ne 1 ]; then
  printf 'Usage: %s /absolute/path/to/extracted-release\n' "$0" >&2
  exit 64
fi

release_input=$1
site_input=${BEBES_SITE_ROOT:-/home/amds/bebes.agmedia.rocks}
account_input=${BEBES_ACCOUNT_ROOT:-/home/amds}

release_root=$(cd -- "$release_input" 2>/dev/null && pwd -P) || {
  printf 'ERROR: release directory is unavailable.\n' >&2
  exit 66
}

site_root=$(cd -- "$site_input" 2>/dev/null && pwd -P) || {
  printf 'ERROR: site directory is unavailable.\n' >&2
  exit 66
}

account_root=$(cd -- "$account_input" 2>/dev/null && pwd -P) || {
  printf 'ERROR: account root is unavailable.\n' >&2
  exit 66
}

case "$site_root" in
  "$account_root"/*) ;;
  *)
    printf 'ERROR: site root must be inside %s.\n' "$account_root" >&2
    exit 64
    ;;
esac

if [ "$site_root" = "$account_root" ] || [ "$site_root" = "/" ]; then
  printf 'ERROR: refusing broad deployment target.\n' >&2
  exit 64
fi

case "$release_root/" in
  "$site_root/"*)
    printf 'ERROR: release directory must not be the site root or live inside it.\n' >&2
    exit 64
    ;;
esac

case "$site_root/" in
  "$release_root/"*)
    printf 'ERROR: site root must not be inside the release directory.\n' >&2
    exit 64
    ;;
esac

critical_php_files=(
  catalog/controller/startup/router.php
  catalog/controller/extension/payment/wspay.php
  catalog/controller/extension/payment/kekspay.php
  catalog/controller/extension/payment/cod.php
  catalog/controller/checkout/success.php
  system/library/bebes_upload_guard.php
  system/library/cart/user.php
  admin/controller/common/login.php
)

required_release_files=(
  .htaccess
  index.php
  admin/controller/.htaccess
  image/.htaccess
  image/catalog/.htaccess
  system/.htaccess
  tools/php-production.user.ini
  "${critical_php_files[@]}"
)

for required in "${required_release_files[@]}"; do
  if [ ! -f "$release_root/$required" ] || [ -L "$release_root/$required" ]; then
    printf 'ERROR: incomplete release, missing or unsafe %s.\n' "$required" >&2
    exit 65
  fi
done

for production_config in config.php admin/config.php; do
  if [ ! -f "$site_root/$production_config" ] || [ -L "$site_root/$production_config" ]; then
    printf 'ERROR: production config is missing or is a symlink: %s\n' "$production_config" >&2
    exit 65
  fi
done

if [ ! -f "$site_root/.htaccess" ] || [ -L "$site_root/.htaccess" ] || ! grep -qF 'Require all denied' "$site_root/.htaccess"; then
  printf 'ERROR: production is not confirmed offline with the deny-all .htaccess.\n' >&2
  exit 77
fi

for required_command in rsync find tr; do
  if ! command -v "$required_command" >/dev/null 2>&1; then
    printf 'ERROR: required command is unavailable: %s\n' "$required_command" >&2
    exit 69
  fi
done

hash_tool=''
if command -v sha256sum >/dev/null 2>&1; then
  hash_tool=$(command -v sha256sum)
elif command -v shasum >/dev/null 2>&1; then
  hash_tool=$(command -v shasum)
fi

if [ -z "$hash_tool" ]; then
  printf 'ERROR: sha256sum or shasum is required.\n' >&2
  exit 69
fi

hash_file() {
  if [ "$(basename -- "$hash_tool")" = 'sha256sum' ]; then
    "$hash_tool" "$1" | awk '{print $1}'
  else
    "$hash_tool" -a 256 "$1" | awk '{print $1}'
  fi
}

php_bin=${BEBES_PHP_BIN:-/opt/cpanel/ea-php74/root/usr/bin/php}
if [ ! -x "$php_bin" ]; then
  php_bin=$(command -v php || true)
fi

if [ -z "$php_bin" ] || [ ! -x "$php_bin" ]; then
  printf 'ERROR: PHP CLI was not found for lint verification.\n' >&2
  exit 69
fi

lint_critical_php() {
  lint_root=$1

  for critical_php in "${critical_php_files[@]}"; do
    if ! "$php_bin" -l "$lint_root/$critical_php" >/dev/null; then
      printf 'ERROR: PHP lint failed: %s\n' "$critical_php" >&2
      return 65
    fi
  done
}

# Fail before touching production if a critical release file cannot be parsed by
# the same PHP CLI that will validate the staged copy.
lint_critical_php "$release_root"

config_hash_before=$(hash_file "$site_root/config.php")
admin_config_hash_before=$(hash_file "$site_root/admin/config.php")

timestamp=$(date -u '+%Y%m%dT%H%M%SZ')
incident_root="$account_root/incident-bebes-20260828"
quarantine_root="$incident_root/predeploy-$timestamp"
stage_root="$account_root/.bebes-release-stage-$timestamp"
old_site_root="$quarantine_root/site-before-deploy"

if [ -L "$incident_root" ]; then
  printf 'ERROR: incident directory must not be a symlink.\n' >&2
  exit 65
fi

if [ -e "$quarantine_root" ] || [ -L "$quarantine_root" ] || [ -e "$stage_root" ] || [ -L "$stage_root" ]; then
  printf 'ERROR: staging or quarantine target already exists; wait one second and retry.\n' >&2
  exit 75
fi

mkdir -p -- "$incident_root"
chmod 0700 "$incident_root"
mkdir -- "$quarantine_root" "$stage_root"
chmod 0700 "$quarantine_root"
chmod 0755 "$stage_root"

preserved_paths=()
site_moved=0
stage_activated=0

rollback_on_exit() {
  exit_status=$?
  trap - EXIT HUP INT TERM

  if [ "$exit_status" -eq 0 ]; then
    return
  fi

  # If the first rename of the final swap succeeded but the second did not,
  # restore the original deny-all webroot first.
  if [ "$site_moved" -eq 1 ] && [ "$stage_activated" -eq 0 ] && [ ! -e "$site_root" ] && [ -d "$old_site_root" ]; then
    mv -- "$old_site_root" "$site_root" || true
    site_moved=0
  fi

  # Business-data directories are moved, not copied, for a quick same-filesystem
  # deployment. Put them back if staging fails before activation.
  if [ "$stage_activated" -eq 0 ] && [ -d "$site_root" ] && [ -d "$stage_root" ]; then
    for relative_path in "${preserved_paths[@]}"; do
      if { [ -e "$stage_root/$relative_path" ] || [ -L "$stage_root/$relative_path" ]; } && \
         [ ! -e "$site_root/$relative_path" ] && [ ! -L "$site_root/$relative_path" ]; then
        mkdir -p -- "$site_root/$(dirname -- "$relative_path")"
        mv -- "$stage_root/$relative_path" "$site_root/$relative_path" || true
      fi
    done
  fi

  if [ "$stage_activated" -eq 0 ] && [ -d "$stage_root" ]; then
    mv -- "$stage_root" "$quarantine_root/failed-stage" || true
  fi

  printf 'ERROR: deployment stopped safely; production remains on deny-all. Review %s.\n' "$quarantine_root" >&2
  exit "$exit_status"
}

trap rollback_on_exit EXIT
trap 'exit 129' HUP
trap 'exit 130' INT
trap 'exit 143' TERM

printf '%s  %s\n' "$config_hash_before" "$site_root/config.php" > "$quarantine_root/config-before.sha256"
printf '%s  %s\n' "$admin_config_hash_before" "$site_root/admin/config.php" >> "$quarantine_root/config-before.sha256"

# Only reviewed release code enters the new webroot. Runtime state and business
# data are handled explicitly below.
rsync -a \
  --exclude='/.htaccess' \
  --exclude='/config.php' \
  --exclude='/admin/config.php' \
  --exclude='/.git/' \
  --exclude='/.gitattributes' \
  --exclude='/.gitignore' \
  --exclude='/.well-known/' \
  --exclude='/README.md' \
  --exclude='/DEPLOYMENT.md' \
  --exclude='/MANIFEST.sha256' \
  --exclude='/tools/' \
  --exclude='/sql/' \
  --exclude='/_ocmod/' \
  --exclude='/_packages/' \
  --exclude='/_incident/' \
  --exclude='/incident-*' \
  --exclude='/image/catalog/' \
  --exclude='/image/cache/' \
  --exclude='/system/storage/cache/' \
  --exclude='/system/storage/logs/' \
  --exclude='/system/storage/session/' \
  --exclude='/system/storage/upload/' \
  --exclude='/system/storage/download/' \
  --exclude='/system/storage/modification/' \
  --exclude='/system/storage/marketplace/' \
  --exclude='/vqmod/vqcache/' \
  --exclude='/vqmod/logs/' \
  --exclude='/vqmod/checked.cache' \
  --exclude='/vqmod/mods.cache' \
  "$release_root/" "$stage_root/"

mkdir -p -- "$stage_root/admin"
cp -p -- "$site_root/config.php" "$stage_root/config.php"
cp -p -- "$site_root/admin/config.php" "$stage_root/admin/config.php"
cp -p -- "$site_root/.htaccess" "$stage_root/.htaccess"
cp -p -- "$release_root/.htaccess" "$stage_root/.htaccess.next"
cp -p -- "$release_root/tools/php-production.user.ini" "$stage_root/.user.ini"
chmod 0644 "$stage_root/.htaccess" "$stage_root/.htaccess.next" "$stage_root/.user.ini"

move_business_directory() {
  relative_path=$1
  source_path="$site_root/$relative_path"
  destination_path="$stage_root/$relative_path"

  if [ -L "$source_path" ]; then
    printf 'ERROR: refusing symlinked business-data path: %s\n' "$relative_path" >&2
    return 65
  fi

  mkdir -p -- "$(dirname -- "$destination_path")"

  if [ -d "$source_path" ]; then
    if [ -e "$destination_path" ] || [ -L "$destination_path" ]; then
      printf 'ERROR: clean stage unexpectedly contains %s.\n' "$relative_path" >&2
      return 65
    fi

    mv -- "$source_path" "$destination_path"
    preserved_paths+=("$relative_path")
  else
    mkdir -p -- "$destination_path"
    chmod 0755 "$destination_path"
  fi
}

move_business_directory 'image/catalog'
move_business_directory 'system/storage/upload'
move_business_directory 'system/storage/download'

if [ -e "$site_root/.well-known" ] || [ -L "$site_root/.well-known" ]; then
  move_business_directory '.well-known'
fi

# Quarantine executable files, nested Apache/PHP configuration and every
# symlink/special file from the preserved data. Passive business data remains.
suspicious_data_root="$quarantine_root/suspicious-preserved-data"
suspicious_data_count=0

quarantine_preserved_file() {
  source_path=$1
  relative_path=${source_path#"$stage_root/"}
  destination_path="$suspicious_data_root/$relative_path"

  mkdir -p -- "$(dirname -- "$destination_path")"
  mv -- "$source_path" "$destination_path"
  suspicious_data_count=$((suspicious_data_count + 1))
}

for preserved_path in "${preserved_paths[@]}"; do
  while IFS= read -r -d '' preserved_file; do
    relative_path=${preserved_file#"$stage_root/"}

    if [ -L "$preserved_file" ] || [ ! -f "$preserved_file" ]; then
      quarantine_preserved_file "$preserved_file"
      continue
    fi

    lowercase_path=$(printf '%s' "$relative_path" | tr '[:upper:]' '[:lower:]')
    case "$lowercase_path" in
      *.php*|*.pht*|*.phar*|*.phps*|*.inc|*.cgi*|*.fcgi*|*.pl|*.py|*.sh|*.bash|*.zsh|*.rb|*.lua|*.shtml*|*.htaccess*|*.htpasswd*|*.user.ini*|*php.ini)
        quarantine_preserved_file "$preserved_file"
        ;;
      *)
        ;;
    esac
  done < <(find "$stage_root/$preserved_path" -xdev ! -type d -print0)
done

# Reinstate the reviewed media guard after quarantining any old nested rules.
mkdir -p -- "$stage_root/image/catalog"
cp -p -- "$release_root/image/catalog/.htaccess" "$stage_root/image/catalog/.htaccess"
chmod 0644 "$stage_root/image/catalog/.htaccess"

for runtime_directory in \
  system/storage/cache \
  system/storage/logs \
  system/storage/session \
  system/storage/modification \
  system/storage/marketplace \
  image/cache \
  vqmod/vqcache \
  vqmod/logs; do
  mkdir -p -- "$stage_root/$runtime_directory"
  chmod 0755 "$stage_root/$runtime_directory"
done

config_hash_after=$(hash_file "$stage_root/config.php")
admin_config_hash_after=$(hash_file "$stage_root/admin/config.php")

if [ "$config_hash_before" != "$config_hash_after" ] || [ "$admin_config_hash_before" != "$admin_config_hash_after" ]; then
  printf 'ERROR: production config changed while building the clean stage.\n' >&2
  exit 1
fi

lint_critical_php "$stage_root"

if ! grep -qF 'Require all denied' "$stage_root/.htaccess"; then
  printf 'ERROR: clean stage lost the deny-all rule.\n' >&2
  exit 77
fi

# Same-filesystem directory renames keep the final cutover as short and atomic
# as possible. The rollback trap restores the old root if the second rename
# fails for any reason.
mv -- "$site_root" "$old_site_root"
site_moved=1
mv -- "$stage_root" "$site_root"
stage_activated=1

trap - EXIT HUP INT TERM

printf 'Clean code staged successfully; production still uses deny-all.\n'
printf 'Previous webroot quarantine: %s\n' "$old_site_root"
printf 'Suspicious preserved-data files quarantined: %s\n' "$suspicious_data_count"
printf 'Prepared production rules: %s/.htaccess.next\n' "$site_root"
printf 'Do not activate them until database cleanup and maintenance-mode checks are complete.\n'
