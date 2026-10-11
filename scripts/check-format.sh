#!/usr/bin/env bash
#
# Prettier check for fork-owned files only.
#
# Fork-owned = added since the integrated upstream base commit (UPSTREAM.md, "Vollständige Commit-ID").
# Upstream files are intentionally not formatted as a whole: they keep the upstream formatting so that
# upstream syncs stay small (ADR 0005, docs/agents/development-conventions.md "Formatting").
#
# Usage:
#   bash scripts/check-format.sh           # check (exit 1 on style issues)
#   bash scripts/check-format.sh --write   # format the fork-owned files
#   bash scripts/check-format.sh --staged  # check only the staged fork-owned files (pre-commit hook)
#
# Needs the full git history of the upstream base commit (CI: actions/checkout with fetch-depth: 0).

set -euo pipefail

cd "$(dirname "$0")/.."

BASE=$(grep -o -E '[0-9a-f]{40}' UPSTREAM.md | head -1)

if [ -z "$BASE" ] || ! git cat-file -e "$BASE^{commit}" 2>/dev/null; then
    echo "check-format: upstream base commit from UPSTREAM.md not available (shallow clone?)" >&2
    exit 2
fi

MODE="--check"
STAGED=0
case "${1:-}" in
    --write) MODE="--write" ;;
    --staged) STAGED=1 ;;
esac

PATHS=('application/**/*.php' 'tests/**/*.php' 'assets/**/*.js' 'assets/**/*.scss')

# Added since the base (compared with the working tree) plus new files not yet tracked by git.
mapfile -t FILES < <(
    {
        git diff --name-only --diff-filter=A "$BASE" -- "${PATHS[@]}"
        git ls-files --others --exclude-standard -- "${PATHS[@]}"
    } |
        grep -v -E '^application/language/|\.min\.js$' |
        sort -u |
        while read -r file; do [ -f "$file" ] && echo "$file"; done
)

if [ "$STAGED" -eq 1 ] && [ "${#FILES[@]}" -gt 0 ]; then
    mapfile -t FILES < <(
        comm -12 <(printf '%s\n' "${FILES[@]}") <(git diff --cached --name-only --diff-filter=ACM | sort -u)
    )
fi

if [ "$STAGED" -eq 1 ]; then
    for file in "${FILES[@]}"; do
        git diff --quiet -- "$file" ||
            echo "check-format: $file is partially staged, the working-tree version is checked" >&2
    done
fi

if [ "${#FILES[@]}" -eq 0 ]; then
    echo "check-format: no fork-owned files to check"
    exit 0
fi

npx prettier "$MODE" "${FILES[@]}"
