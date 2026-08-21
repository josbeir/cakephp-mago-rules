# Repository Guidelines

## Project Structure & Module Organization

- `src/` contains the PHP extension API and Mago linter rules. Keep reusable
  helpers under `src/Linter/` and individual rules under `src/Linter/Rules/`.
- `bin/mago-cakephp-worker` is the package-owned PHP worker launched by Mago.
- `mago.cakephp.toml` is the importable CakePHP formatter and native-rule
  configuration.
- `tests/Unit/` holds PHPUnit tests. `tests/corpus/` verifies extension-worker
  behavior, while `tests/cakephp5/` configures checks against CakePHP 5.x.
- `docs/compatibility-matrix.md` is the authoritative mapping from CakePHP
  CodeSniffer behavior to formatter, native Mago, or extension rules.

## Build, Test, and Development Commands

- `composer update --no-interaction --prefer-dist` installs or refreshes PHP
  dependencies and the Mago binary launcher.
- `composer test` runs PHPUnit unit tests.
- `composer run validate-extension` starts the corpus worker and verifies Mago
  extension registration.
- `composer run lint-corpus` verifies expected external-rule diagnostics against
  the focused corpus; fixtures use `@mago-expect` annotations intentionally.
- `scripts/check-cakephp-5.sh /path/to/cakephp` validates rules against an
  existing shallow CakePHP 5.x checkout. Omit the argument to clone one.

## Coding Style & Naming Conventions

Use PHP 8.1-compatible, strict PHP: start files with `declare(strict_types=1);`,
use four-space indentation, and keep classes `final` unless extension is
intentional. Name rule classes with a `Rule` suffix, for example
`TraitSuffixRule`; use vendor-qualified kebab-case Mago codes such as
`mago-cakephp/trait-suffix`. Prefer narrow AST targets and exact `Span` edits.
Run `mago format` for formatting; do not add unrelated default Mago lint rules
to the CakePHP compatibility allow-list.

## Testing Guidelines

Add a PHPUnit test for registration or deterministic helper behavior, plus a
corpus case for each diagnostic or fix. Update the compatibility matrix whenever
a CakePHP CodeSniffer rule changes status. Validate formatter/fixer changes
against CakePHP 5.x before merging.

## Commit & Pull Request Guidelines

This workspace has no accessible Git history, so no local convention can be
derived. Use concise imperative Conventional Commit-style subjects, for example
`feat: add CakePHP docblock rule`. Pull requests should explain compatibility
impact, list matrix changes, include validation output, and link relevant Mago
or CakePHP issues.
