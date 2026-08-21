#!/usr/bin/env bash
set -euo pipefail

checkout_dir="${1:-}"
temporary_checkout=""

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

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
config="$repo_root/tests/cakephp6/mago.toml"

"$repo_root/vendor/bin/mago" --workspace "$checkout_dir" --config "$config" extension validate
"$repo_root/vendor/bin/mago" --workspace "$checkout_dir" --config "$config" lint --only \
    mago-cakephp/trait-suffix,mago-cakephp/public-method-underscore,mago-cakephp/elseif,mago-cakephp/function-docblock,mago-cakephp/docblock-tag-spacing,mago-cakephp/throws-tag,mago-cakephp/inherit-doc
