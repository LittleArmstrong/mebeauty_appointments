# Docker Compose stack of the main checkout, sourced by scripts/test.sh, scripts/composer.sh,
# scripts/migration-test-db.sh, tests/smoke/lib.sh and the git hooks.
#
# A linked git worktree (/implement-spec puts them under .claude/worktrees/) has no stack of its own: its
# docker-compose.yml would start a second project with clashing ports. The containers mount the main checkout at
# /var/www/html, and a worktree inside it is visible there too. So every command goes to the main checkout's
# project, and php-fpm works in the worktree's directory. nginx serves /var/www/html, so a worktree's own code
# answers below STACK_URL_PATH (http://localhost/.claude/worktrees/<name>/). A worktree's .git file names its git
# directory by host path; stack_link_host_path makes that path resolve inside php-fpm (GIT_DIR would not do:
# Composer strips it from its scripts).
#
# Provides:
#   STACK_MAIN_DIR     host path of the main checkout
#   STACK_IN_WORKTREE  true in a linked worktree
#   STACK_WORKDIR      path of the current checkout inside php-fpm (empty: worktree outside the mount)
#   STACK_URL_PATH     URL path of the current checkout below nginx's root ("" in the main checkout)
#   stack ...          docker compose of the main checkout
#   stack_exec ...     docker compose exec -T in the current checkout ([-e VAR=value ...] <service> <command>)
#   stack_link_host_path  in a worktree: link the main checkout's host path to /var/www/html inside php-fpm

STACK_MAIN_DIR=$(cd "$(git rev-parse --path-format=absolute --git-common-dir)/.." && pwd)
STACK_TOPLEVEL=$(git rev-parse --show-toplevel)
STACK_IN_WORKTREE=false
STACK_WORKDIR=/var/www/html
STACK_URL_PATH=""

if [ "$STACK_TOPLEVEL" != "$STACK_MAIN_DIR" ]; then
    STACK_IN_WORKTREE=true
    case "$STACK_TOPLEVEL" in
        "$STACK_MAIN_DIR"/*)
            STACK_URL_PATH="/${STACK_TOPLEVEL#"$STACK_MAIN_DIR"/}"
            STACK_WORKDIR="/var/www/html$STACK_URL_PATH"
            ;;
        *) STACK_WORKDIR="" ;;
    esac
fi

stack() {
    docker compose -f "$STACK_MAIN_DIR/docker-compose.yml" "$@"
}

stack_exec() {
    stack exec -T -w "$STACK_WORKDIR" "$@"
}

stack_php_running() {
    [ -n "$STACK_WORKDIR" ] && [ -n "$(stack ps -q --status running php-fpm 2>/dev/null)" ]
}

stack_link_host_path() {
    [ "$STACK_IN_WORKTREE" = true ] || return 0
    stack exec -T -u root php-fpm sh -c '[ -e "$1" ] || { mkdir -p "$(dirname "$1")" && ln -s /var/www/html "$1"; }' \
        sh "$STACK_MAIN_DIR"
}
