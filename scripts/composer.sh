#!/usr/bin/env bash
#
# Run `composer <args>` inside php-fpm in the current checkout, the main checkout or a linked worktree under
# .claude/worktrees/ alike (scripts/lib/stack.sh). A plain `docker compose exec -T php-fpm composer …` works in the
# container's default directory, the main checkout: from a worktree it would rewrite the main checkout's files.
#
# Usage (on the host, from anywhere inside the checkout; the stack must be running):
#   bash scripts/composer.sh scratch:index
#   bash scripts/composer.sh check:scratch
#   bash scripts/composer.sh test -- --filter ScratchIndexTest
#
# A worktree needs its own vendor/ for commands that load the autoloader (test, check:*); `bash scripts/test.sh`
# creates it on its first run. Exits 2 when the stack is not running or the worktree is invisible to the containers.

set -euo pipefail

cd "$(dirname "$0")/.."
. scripts/lib/stack.sh

[ -n "$STACK_WORKDIR" ] || {
    echo "composer.sh: Worktree liegt außerhalb von $STACK_MAIN_DIR – die Container sehen ihn nicht" >&2
    exit 2
}
stack_php_running || {
    echo "composer.sh: php-fpm läuft nicht – docker compose up -d im Hauptcheckout (docs/docker.md)" >&2
    exit 2
}
stack_link_host_path

stack_exec php-fpm composer "$@"
