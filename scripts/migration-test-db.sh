#!/usr/bin/env bash
#
# Run migrations against a throwaway database without editing config.php.
#
# The php-fpm container receives EA_DB_NAME, which application/config/database.php
# honours for CLI requests only. config.php is never touched, so an aborted run
# can at worst leave behind an orphaned ea_test_* database (cleanup hint below).
#
# Usage (from the repository root, Docker Compose stack running):
#   scripts/migration-test-db.sh fresh [--keep]
#       Empty database -> `console install` (fresh migration + seed).
#   scripts/migration-test-db.sh copy <source-db> [--set-version <n>] [--keep]
#       Copy of <source-db> -> optionally force ea_migrations.version to <n>
#       (simulates an older/newer tracker state) -> `console migrate`.
#
# Options:
#   --keep   Keep the test database for inspection (prints its name and a drop command).
#
# Orphan cleanup:
#   docker compose exec -T -e MYSQL_PWD=secret mysql mysql -uroot -e "SHOW DATABASES LIKE 'ea\_test\_%'"

set -euo pipefail

MYSQL_ROOT_PASSWORD=secret # dev-only value from docker-compose.yml
DB_APP_USER=user

usage() {
    sed -n '2,20p' "$0" | sed 's/^# \{0,1\}//'
    exit "${1:-1}"
}

mode="${1:-}"
[[ "$mode" == "-h" || "$mode" == "--help" ]] && usage 0
[[ "$mode" == "fresh" || "$mode" == "copy" ]] || usage
shift

source_db=""
if [[ "$mode" == "copy" ]]; then
    source_db="${1:-}"
    [[ "$source_db" =~ ^[A-Za-z0-9_]+$ ]] || { echo "copy: valid <source-db> required" >&2; usage; }
    shift
fi

keep=false
set_version=""
while [[ $# -gt 0 ]]; do
    case "$1" in
        --keep) keep=true ;;
        --set-version)
            [[ "$mode" == "copy" && "${2:-}" =~ ^[0-9]+$ ]] || { echo "--set-version <n> requires copy mode" >&2; usage; }
            set_version="$2"
            shift
            ;;
        *) usage ;;
    esac
    shift
done

test_db="ea_test_${mode}_$(date +%s)_$$"

mysql_root() {
    docker compose exec -T -e MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql mysql -uroot "$@"
}

cleanup() {
    if [[ "$keep" == true ]]; then
        echo "⇾ Kept database: $test_db"
        echo "  Drop it with: docker compose exec -T -e MYSQL_PWD=$MYSQL_ROOT_PASSWORD mysql mysql -uroot -e \"DROP DATABASE \\\`$test_db\\\`; REVOKE ALL PRIVILEGES ON \\\`$test_db\\\`.* FROM '$DB_APP_USER'@'%';\""
        return
    fi
    echo "⇾ Dropping $test_db"
    mysql_root -e "DROP DATABASE IF EXISTS \`$test_db\`; REVOKE ALL PRIVILEGES ON \`$test_db\`.* FROM '$DB_APP_USER'@'%';" ||
        echo "WARNING: cleanup failed, drop $test_db manually" >&2
}

echo "⇾ Creating $test_db"
mysql_root -e "CREATE DATABASE \`$test_db\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; GRANT ALL PRIVILEGES ON \`$test_db\`.* TO '$DB_APP_USER'@'%';"
trap cleanup EXIT

if [[ "$mode" == "copy" ]]; then
    echo "⇾ Copying $source_db → $test_db"
    docker compose exec -T -e MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql bash -o pipefail -c \
        'mysqldump -uroot --single-transaction --no-tablespaces "$1" | mysql -uroot "$2"' bash "$source_db" "$test_db"

    if [[ -n "$set_version" ]]; then
        echo "⇾ Forcing migration tracker to $set_version"
        mysql_root "$test_db" -e "UPDATE ea_migrations SET version = $set_version;"
    fi
fi

console_command=(migrate)
[[ "$mode" == "fresh" ]] && console_command=(install)

echo "⇾ Running console ${console_command[*]} on $test_db"
docker compose exec -T -e EA_DB_NAME="$test_db" php-fpm php index.php console "${console_command[@]}"

echo "⇾ Migration tracker:"
mysql_root "$test_db" -e "SELECT version FROM ea_migrations;"
