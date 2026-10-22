#!/usr/bin/env bash
#
# Upload the updated static files to the production server over FTP.
#
# Nothing is written to the server unless you pass --go. The default mode
# downloads the remote copies, compares them with the local files and prints
# exactly what would change.
#
# Credentials come from the environment so they never appear in this file:
#
#   export FTP_USER=admin_dhikala
#   export FTP_PASS='...'
#   export REMOTE_ROOT=/          # the directory that holds index.html
#
# Modes:
#   ./upload-to-server.sh --probe   list remote dirs, to find REMOTE_ROOT
#   ./upload-to-server.sh --ls      list REMOTE_ROOT (or --ls sub/dir)
#   ./upload-to-server.sh           dry run: show what would change
#   ./upload-to-server.sh --go      back up, upload, verify
#
set -euo pipefail

FTP_HOST="${FTP_HOST:-dhikala-corbett.com}"
FTP_PORT="${FTP_PORT:-8014}"
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
MANIFEST="$ROOT_DIR/deploy/upload-manifest.txt"
BACKUP_ROOT="${BACKUP_ROOT:-$ROOT_DIR/deploy/_ftp_backup}"

CURL_CFG=""; WORK=""; BACKUP=""
FAILED=0
REQUIRED=(); OPTIONAL=(); NEW=(); DIFFER=(); SAME=()

die()  { printf '\nABORT: %s\n' "$*" >&2; exit 1; }
note() { printf '%s\n' "$*"; }
usage() { sed -n '3,20p' "${BASH_SOURCE[0]}" | sed 's/^# \{0,1\}//'; exit 1; }

cleanup() {
    [ -n "$CURL_CFG" ] && rm -f "$CURL_CFG"
    [ -n "$WORK" ] && rm -rf "$WORK"
    return 0
}

# ---------------------------------------------------------------- settings --
need_creds() {
    : "${FTP_USER:?export FTP_USER first (see the header)}"
    : "${FTP_PASS:?export FTP_PASS first (see the header)}"
    REMOTE_ROOT="${REMOTE_ROOT:-/}"         # --probe runs before REMOTE_ROOT is known
    REMOTE_ROOT="${REMOTE_ROOT%/}"          # "/" -> "", "/htdocs/" -> "/htdocs"

    CURL_CFG="$(mktemp)"; chmod 600 "$CURL_CFG"
    WORK="$(mktemp -d)"
    trap cleanup EXIT
    # password goes in a 0600 config file, never in argv (invisible to ps)
    local esc
    esc="$(printf '%s:%s' "$FTP_USER" "$FTP_PASS" | sed 's/\\/\\\\/g; s/"/\\"/g')"
    printf 'user = "%s"\n' "$esc" > "$CURL_CFG"

    CURL=(curl --silent --show-error --fail --config "$CURL_CFG"
          --connect-timeout 20 --max-time 300 --retry 2 --retry-delay 2
          --ftp-skip-pasv-ip)
}

remote_url() { printf 'ftp://%s:%s%s' "$FTP_HOST" "$FTP_PORT" "$1"; }

# ---------------------------------------------------------------- manifest --
read_manifest() {
    [ -f "$MANIFEST" ] || die "manifest not found: $MANIFEST"
    local in_optional=0 line f missing=0
    while IFS= read -r line; do
        case "$line" in
            ''|'# '*) continue ;;
            '--- required ---') in_optional=0; continue ;;
            '--- optional'*)    in_optional=1; continue ;;
        esac
        if [ "$in_optional" -eq 1 ]; then OPTIONAL+=("$line"); else REQUIRED+=("$line"); fi
    done < "$MANIFEST"

    [ "${#REQUIRED[@]}" -gt 0 ] || die "manifest lists no files"
    for f in "${REQUIRED[@]}"; do
        if [ ! -f "$ROOT_DIR/$f" ]; then note "  MISSING locally: $f"; missing=1
        elif [ "$(stat -c%s "$ROOT_DIR/$f")" -lt 500 ]; then
            note "  SUSPICIOUS (under 500 bytes): $f"; missing=1
        fi
    done
    [ "$missing" -eq 0 ] || die "local files look wrong - refusing to touch the server"
    note "manifest: ${#REQUIRED[@]} required file(s), ${#OPTIONAL[@]} optional (skipped)"
}

# ------------------------------------------------------------------ probe ---
do_probe() {
    need_creds
    local p out
    for p in / /htdocs /public_html /httpdocs /www /web /wwwroot /public; do
        if out="$("${CURL[@]}" "$(remote_url "$p/")" 2>/dev/null)"; then
            printf '  %-14s reachable - %d entries\n' "$p" "$(printf '%s' "$out" | grep -c .)"
            printf '%s\n' "$out" | awk '{print $NF}' \
                | grep -iE '^(index\.html|about_dhikala\.html|forest-rest-house)$' \
                | sed 's/^/        contains: /' || true
        else
            printf '  %-14s not usable\n' "$p"
        fi
    done
    note ""
    note "Choose the path that contains index.html and export it as REMOTE_ROOT."
}

# read-only listing, e.g. ./upload-to-server.sh --ls forest-rest-house
do_ls() {
    need_creds
    local target="${1:-$REMOTE_ROOT}"
    target="${target%/}"
    note "listing ${target:-/}/ :"
    "${CURL[@]}" "$(remote_url "$target/")" | awk '{print $NF}' | sort | sed 's/^/  /'
}

# --------------------------------------------------------------- transfers --
fetch_file() {                       # -> 0 downloaded, 1 not present remotely
    local rel="$1" dest="$2"
    if "${CURL[@]}" --output "$dest" "$(remote_url "$REMOTE_ROOT/$rel")" 2>/dev/null; then
        return 0
    fi
    rm -f "$dest"
    return 1
}

upload_file() {
    "${CURL[@]}" --ftp-create-dirs --upload-file "$ROOT_DIR/$1" \
        "$(remote_url "$REMOTE_ROOT/$1")"
}

md5_of() { md5sum "$1" | cut -d' ' -f1; }
flat()   { printf '%s' "$1" | tr '/' '_'; }

show_group() {
    local label="$1"; shift
    [ "$#" -eq 0 ] && return 0
    printf '%s:\n' "$label"
    local f
    for f in "$@"; do printf '    %s\n' "$f"; done
    printf '\n'
}

# ------------------------------------------------------------------ phases --
compare() {
    local f dest
    for f in "${REQUIRED[@]}"; do
        dest="$WORK/$(flat "$f")"
        if ! fetch_file "$f" "$dest"; then NEW+=("$f"); continue; fi
        if [ "$(md5_of "$dest")" = "$(md5_of "$ROOT_DIR/$f")" ]; then SAME+=("$f"); else DIFFER+=("$f"); fi
    done
    printf '\n%-13s %d\n' 'NEW:'      "${#NEW[@]}"
    printf '%-13s %d   <- these get overwritten (backed up first)\n' 'DIFFERENT:' "${#DIFFER[@]}"
    printf '%-13s %d\n\n' 'ALREADY SAME:' "${#SAME[@]}"
    show_group "NEW (not yet on the server)" "${NEW[@]+"${NEW[@]}"}"
    show_group "DIFFERENT (would be overwritten)" "${DIFFER[@]+"${DIFFER[@]}"}"
}

backup() {
    local stamp f dest
    stamp="$(date +%Y%m%d-%H%M%S)"
    BACKUP="$BACKUP_ROOT/$stamp"
    mkdir -p "$BACKUP"
    for f in "${DIFFER[@]}"; do
        dest="$BACKUP/$f"; mkdir -p "$(dirname "$dest")"
        cp "$WORK/$(flat "$f")" "$dest"
    done
    note "backup of ${#DIFFER[@]} overwritten file(s): $BACKUP"
}

verify() {
    local f dest bad=0
    for f in "$@"; do
        dest="$WORK/v_$(flat "$f")"
        if ! fetch_file "$f" "$dest"; then
            printf '  FAIL (cannot read back)  %s\n' "$f"; bad=$((bad+1)); continue
        fi
        if [ "$(md5_of "$dest")" = "$(md5_of "$ROOT_DIR/$f")" ]; then
            printf '  ok    %s\n' "$f"
        else
            printf '  FAIL (checksum differs)  %s\n' "$f"; bad=$((bad+1))
        fi
        rm -f "$dest"
    done
    [ "$bad" -eq 0 ] || { FAILED=1; printf '\n%d file(s) FAILED verification\n' "$bad"; }
}

# -------------------------------------------------------------------- main --
precheck_env() {
    local missing=()
    [ -n "${FTP_USER:-}" ]    || missing+=("FTP_USER")
    [ -n "${FTP_PASS:-}" ]    || missing+=("FTP_PASS")
    [ -n "${REMOTE_ROOT:-}" ] || missing+=("REMOTE_ROOT")
    if [ "${#missing[@]}" -gt 0 ]; then
        die "environment not set up yet - missing: ${missing[*]}

  export FTP_USER=admin_dhikala
  export FTP_PASS='your-ftp-password'
  export REMOTE_ROOT=/          # run --probe first to find the right path

  All three must be exported in THIS shell (they do not persist between
  terminals), then run the script again."
    fi
}

MODE="${1:-dry-run}"
read_manifest

if [ "$MODE" = "--probe" ]; then do_probe; exit 0; fi
if [ "$MODE" = "--ls" ]; then do_ls "${2:-}"; exit 0; fi
[ "$MODE" = "--go" ] || [ "$MODE" = "dry-run" ] || usage

precheck_env

need_creds

note ""
note "preflight: checking that REMOTE_ROOT='${REMOTE_ROOT:-/}' is the web root"
listing="$("${CURL[@]}" "$(remote_url "$REMOTE_ROOT/")")" \
    || die "cannot list that directory - check REMOTE_ROOT and the credentials"
if printf '%s' "$listing" | grep -q 'index\.html'; then
    note "  index.html found there - looks correct"
else
    note "  WARNING: no index.html in that directory:"
    printf '%s\n' "$listing" | sed 's/^/      /'
    if [ "$MODE" = "--go" ]; then
        die "refusing to upload into a directory that does not contain index.html"
    fi
fi

compare

if [ "$MODE" != "--go" ]; then
    note "DRY RUN - nothing was uploaded. Re-run with --go to apply."
    exit 0
fi

if [ "$(( ${#DIFFER[@]} + ${#NEW[@]} ))" -eq 0 ]; then
    note "nothing to upload - the server already matches."
    exit 0
fi

backup
printf '\nuploading %d file(s)...\n' "$(( ${#DIFFER[@]} + ${#NEW[@]} ))"
for f in "${DIFFER[@]+"${DIFFER[@]}"}" "${NEW[@]+"${NEW[@]}"}"; do
    if upload_file "$f"; then printf '  sent  %s\n' "$f"
    else printf '  FAILED to send  %s\n' "$f"; FAILED=1; fi
done

printf '\nverifying: downloading each file back and comparing checksums...\n'
verify "${DIFFER[@]+"${DIFFER[@]}"}" "${NEW[@]+"${NEW[@]}"}"

if [ "$FAILED" -eq 0 ]; then
    printf '\nDone - all files uploaded and verified.\n'
else
    printf '\nFinished WITH ERRORS - server may be inconsistent. Backup: %s\n' "${BACKUP:-none}" >&2
    exit 1
fi
