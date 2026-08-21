#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
fixture_dir="$(mktemp -d /tmp/cakephp-mago-fixes.XXXXXX)"

cleanup() {
    rm -rf "$fixture_dir"
}
trap cleanup EXIT

mkdir -p "$fixture_dir/src" "$fixture_dir/tests" "$fixture_dir/vendor/cakephp"
cp "$repo_root/tests/fixer/input.php" "$fixture_dir/src/Rules.php"
cp "$repo_root/tests/consumer/mago.toml" "$fixture_dir/mago.toml"
ln -s "$repo_root" "$fixture_dir/vendor/cakephp/mago-rules"

"$repo_root/vendor/bin/mago" --workspace "$fixture_dir" lint --fix --format-after-fix --fail-on-remaining --only \
    mago-cakephp/elseif,mago-cakephp/docblock-tag-spacing,mago-cakephp/inherit-doc

"$repo_root/vendor/bin/mago" --workspace "$fixture_dir" lint --fix --format-after-fix --fail-on-remaining --only \
    mago-cakephp/elseif,mago-cakephp/docblock-tag-spacing,mago-cakephp/inherit-doc

diff -u "$repo_root/tests/fixer/expected.php" "$fixture_dir/src/Rules.php"
