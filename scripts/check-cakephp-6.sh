#!/usr/bin/env bash
set -euo pipefail

checkout_dir=""
temporary_checkout=""

usage() {
    echo "Usage: $0 [--workspace PATH | PATH]"
}

while [[ $# -gt 0 ]]; do
    case "$1" in
        --workspace)
            if [[ $# -lt 2 ]]; then
                echo "Error: --workspace requires a path." >&2
                usage >&2
                exit 2
            fi
            checkout_dir="$2"
            shift 2
            ;;
        --workspace=*)
            checkout_dir="${1#--workspace=}"
            shift
            ;;
        -h|--help)
            usage
            exit 0
            ;;
        -*)
            echo "Error: unknown option $1" >&2
            usage >&2
            exit 2
            ;;
        *)
            if [[ -n "$checkout_dir" ]]; then
                echo "Error: only one workspace may be provided." >&2
                usage >&2
                exit 2
            fi
            checkout_dir="$1"
            shift
            ;;
    esac
done

cleanup() {
    if [[ -n "$temporary_checkout" ]]; then
        rm -rf "$temporary_checkout"
    fi
}
trap cleanup EXIT

if [[ -z "$checkout_dir" ]]; then
    checkout_dir="$(mktemp -d /tmp/cakephp-6.XXXXXX)"
    temporary_checkout="$checkout_dir"
    git clone --depth=1 --filter=blob:none --single-branch --branch 6.x \
        https://github.com/cakephp/cakephp.git "$checkout_dir"
fi

if [[ ! -d "$checkout_dir" ]]; then
    echo "Error: workspace directory does not exist: $checkout_dir" >&2
    exit 2
fi

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
config="$repo_root/tests/cakephp6/mago.toml"

"$repo_root/vendor/bin/mago" --workspace "$checkout_dir" --config "$config" extension validate
"$repo_root/vendor/bin/mago" --workspace "$checkout_dir" --config "$config" lint --only \
    mago-cakephp/trait-suffix,mago-cakephp/public-method-underscore,mago-cakephp/elseif,mago-cakephp/function-docblock,mago-cakephp/docblock-tag-spacing,mago-cakephp/throws-tag,mago-cakephp/inherit-doc
