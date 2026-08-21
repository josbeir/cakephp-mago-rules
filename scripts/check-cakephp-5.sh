#!/usr/bin/env bash
set -euo pipefail

checkout_dir="${1:-/home/josbeir/Sites/cakephp}"
if [[ -z "$checkout_dir" ]]; then
    checkout_dir="$(mktemp -d /tmp/cakephp-5.XXXXXX)"
    git clone --depth=1 --branch 5.x https://github.com/cakephp/cakephp.git "$checkout_dir"
fi

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
config="$repo_root/tests/cakephp5/mago.toml"

"$repo_root/vendor/bin/mago" --workspace "$checkout_dir" --config "$config" extension validate
"$repo_root/vendor/bin/mago" --workspace "$checkout_dir" --config "$config" lint --only \
    block-statement,mago-cakephp/trait-suffix,mago-cakephp/public-method-underscore,mago-cakephp/elseif,mago-cakephp/function-docblock,mago-cakephp/docblock-alignment,mago-cakephp/inherit-doc,mago-cakephp/return-type-docblock
