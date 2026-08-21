# CakePHP rules for Mago

[![Quality gates](https://github.com/josbeir/cakephp-mago-rules/actions/workflows/quality.yml/badge.svg)](https://github.com/josbeir/cakephp-mago-rules/actions/workflows/quality.yml)
[![License](https://img.shields.io/github/license/josbeir/cakephp-mago-rules)](LICENSE)
[![Packagist downloads](https://img.shields.io/packagist/dt/josbeir/cakephp-mago-rules?logo=packagist&label=downloads)](https://packagist.org/packages/josbeir/cakephp-mago-rules)
[![Mago compatibility](https://img.shields.io/badge/Mago-%5E1.47-f97316)](https://mago.carthage.software/)

A Mago-first coding-standard preset for CakePHP applications and plugins. Mago
owns generic formatting, linting, code-quality checks, and fixes; this package
adds CakePHP formatter preferences and the few framework conventions that Mago
cannot express natively.

## Installation

```sh
composer require --dev josbeir/cakephp-mago-rules carthage-software/mago
```

Import the preset from the project's `mago.toml`:

```toml
extends = "vendor/josbeir/cakephp-mago-rules/mago.cakephp.toml"
```

The imported configuration starts the package-owned extension worker. No
copied bootstrap or manual rule registration is needed.

## Usage

Use Mago's normal commands:

```sh
mago format --check
mago lint
```

Apply formatting and safe lint fixes with:

```sh
mago format
mago lint --fix --format-after-fix --fail-on-remaining
```

The preset deliberately keeps Mago's default rule catalogue enabled. Projects
can override native or CakePHP rules after the `extends` declaration:

```toml
[linter.rules]
cyclomatic-complexity = { enabled = false }
"mago-cakephp/function-docblock" = { enabled = false }
```

## Responsibility map

| Concern | Owner |
| --- | --- |
| PSR-12 layout, braces, imports, quotes, commas, casts and return spacing | Mago formatter with CakePHP settings |
| Short arrays, braced blocks, short tags, silenced errors, assignments in conditions and redundant syntax | Native Mago rules |
| Trait `Trait` suffix | `mago-cakephp/trait-suffix` |
| Public method underscore prefix | `mago-cakephp/public-method-underscore` |
| `elseif` spelling | `mago-cakephp/elseif` |
| Function and method docblocks outside tests | `mago-cakephp/function-docblock` |
| One space after PHPDoc tags | `mago-cakephp/docblock-tag-spacing` |
| Exception type on `@throws` | `mago-cakephp/throws-tag` |
| CakePHP `@inheritDoc` spelling and placement | `mago-cakephp/inherit-doc` |
| Chaining methods documented as `@return $this` | `mago-cakephp/chaining-return-type` |

Use `@mago-expect lint:<rule-code>` for an intentional local exception, or a
Mago baseline when migrating an existing project. PHPCS suppression comments
are not interpreted by this package.

## Relationship to CakePHP CodeSniffer

This is not a PHPCS emulator or a promise of sniff-for-sniff parity. Generic
PHPCS and Slevomat concerns belong to Mago's formatter and native linter, even
when diagnostics or fixes differ. Mago may report additional quality or
security findings because its defaults remain enabled, and formatter output is
not expected to be byte-for-byte identical to PHPCBF.

Mago internally understands PHPDoc, but version 1.47's PHP extension SDK exposes
docblocks as source trivia rather than a parsed PHPDoc tree. The extension
therefore implements only the small line-oriented PHPDoc policies listed above.

## Compatibility and development

| Package requirement | Supported version |
| --- | --- |
| PHP | `^8.3` |
| Mago | `^1.47` |
| Primary target | CakePHP 5.x applications and plugins |

Run `composer run cs-check`, `composer test`, `composer run lint-corpus`,
`composer run test-fixes`, and `composer run test-consumer`. To validate the
extension rules against CakePHP itself, pass an existing shallow checkout to
`scripts/check-cakephp-5.sh /path/to/cakephp`; without an argument the script
uses a local checkout when available or clones CakePHP 5.x.
