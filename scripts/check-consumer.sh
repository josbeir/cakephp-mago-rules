#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "$0")/.." && pwd)"
consumer_dir="$(mktemp -d /tmp/cakephp-mago-consumer.XXXXXX)"

cleanup() {
    rm -rf "$consumer_dir"
}
trap cleanup EXIT

cp -R "$repo_root/tests/consumer/." "$consumer_dir"
mkdir -p "$consumer_dir/vendor/josbeir"
ln -s "$repo_root" "$consumer_dir/vendor/josbeir/cakephp-mago-rules"

mago="$repo_root/vendor/bin/mago"
"$mago" --workspace "$consumer_dir" extension validate
"$mago" --workspace "$consumer_dir" format --check
"$mago" --workspace "$consumer_dir" lint
