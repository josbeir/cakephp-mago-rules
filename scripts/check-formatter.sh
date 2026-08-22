#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
fixture_dir="$(mktemp -d /tmp/cakephp-mago-formatter.XXXXXX)"

cleanup() {
    rm -rf "$fixture_dir"
}
trap cleanup EXIT

mkdir -p "$fixture_dir/src" "$fixture_dir/tests" "$fixture_dir/vendor/cakephp"
cp "$repo_root/tests/formatter/input.php" "$fixture_dir/src/Formatter.php"
cp "$repo_root/tests/consumer/mago.toml" "$fixture_dir/mago.toml"
ln -s "$repo_root" "$fixture_dir/vendor/cakephp/mago-rules"

"$repo_root/vendor/bin/mago" --workspace "$fixture_dir" format
diff -u "$repo_root/tests/formatter/expected.php" "$fixture_dir/src/Formatter.php"

# A second pass must make no changes.
"$repo_root/vendor/bin/mago" --workspace "$fixture_dir" format --check
