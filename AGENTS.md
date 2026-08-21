# Repository Guidelines

## Project Structure & Module Organization

- `src/` contains the public extension factory and CakePHP-specific Mago rules.
  Put shared syntax and PHPDoc helpers in `src/Linter/`; keep one rule per file
  under `src/Linter/Rules/`.
- `bin/mago-cakephp-worker` is the package-owned extension host.
- `mago.cakephp.toml` is the consumer preset. It must remain additive to Mago's
  defaults and contain only CakePHP policy.
- `tests/corpus/` covers diagnostics, `tests/fixer/` covers exact safe edits,
  and `tests/consumer/` verifies the installed one-line `extends` workflow.

## Build, Test, and Development Commands

- `composer run cs-check` checks formatting and runs the full shipped preset.
- `composer test` runs PHPUnit registration and helper tests.
- `composer run validate-extension` verifies worker startup and registration.
- `composer run lint-corpus` checks isolated expected diagnostics.
- `composer run test-fixes` compares safe fixes with committed output.
- `composer run test-consumer` runs plain Mago commands as an installed project.
- `scripts/check-cakephp-6.sh /path/to/cakephp` checks custom rules against a
  shallow CakePHP 6.x checkout. Without a path it clones a temporary 6.x tree.

## Coding Style & Rules

Target PHP 8.4+, use strict types and four-space indentation, and keep classes
`final` unless extension is intentional. Rule classes use a `Rule` suffix and
codes use `mago-cakephp/kebab-case`. Prefer Mago syntax nodes and exact `Span`
edits; use source-text parsing only where the extension SDK exposes trivia.
Generic formatting and quality rules belong to Mago, not this extension.

## Testing and Contributions

Every rule needs valid and invalid corpus cases; every edit needs an exact,
idempotent fixer fixture. Run all commands above before opening a pull request.
Use concise Conventional Commit subjects such as `feat: add CakePHP rule`.
Pull requests should describe user-visible configuration changes, rule-code or
fix behavior changes, and CakePHP corpus results.
