# CakePHP CodeSniffer rules for Mago

[![Quality gates](https://github.com/josbeir/cakephp-mago-rules/actions/workflows/quality.yml/badge.svg)](https://github.com/josbeir/cakephp-mago-rules/actions/workflows/quality.yml)
[![License](https://img.shields.io/github/license/josbeir/cakephp-mago-rules)](LICENSE)
[![Packagist downloads](https://img.shields.io/packagist/dt/josbeir/cakephp-mago-rules?logo=packagist&label=downloads)](https://packagist.org/packages/josbeir/cakephp-mago-rules)
[![Mago compatibility](https://img.shields.io/badge/Mago-%5E1.47-f97316)](https://mago.carthage.software/)

An upstream-ready Mago extension that targets compatibility with the CakePHP
CodeSniffer standard. Mago remains the formatter; this package supplies the
CakePHP-specific rules that do not have a native Mago equivalent.

## Installation

```sh
composer require --dev josbeir/cakephp-mago-rules carthage-software/mago
```

Add this to the project `mago.toml`:

```toml
extends = "vendor/josbeir/cakephp-mago-rules/mago.cakephp.toml"
```

The imported configuration starts the package-owned worker. No copied worker
or project bootstrap is required. Projects that combine several extensions in
one PHP worker can still override `[extension-hosts.cakephp]` and use their
own entrypoint.

Run Mago normally. The imported configuration enables the CakePHP-compatible
native and extension rules, while disabling Mago's unrelated default rules.
Projects can explicitly enable additional Mago rules in their own config.

```sh
mago format --check
mago lint
```

## What this does—and does not—replace

This package is a focused CakePHP compatibility layer for Mago, not a
drop-in replacement for PHP_CodeSniffer or every CakePHP CodeSniffer sniff.
Anything marked **planned** below is deliberately not enforced yet.

Mago is a strong fit when you want one fast PHP toolchain for formatting and
linting, safe automatic fixes for supported diagnostics, and CakePHP-specific
checks integrated into the same command and configuration. The package-owned
worker means a consuming project only needs one `extends` entry—no copied
bootstrap file or per-project rule registration.

Keep PHP_CodeSniffer in place when you require complete CakePHP CodeSniffer
coverage, a specific sniff's options or severity behavior, exact PHPCBF
output, or checks that are still planned here (notably detailed PHPDoc rules,
control-structure rules, and filename/type conventions). Run both tools during
the transition if the CakePHP standard is a release gate.

Formatter output is not intended to be byte-for-byte identical to PHPCBF
output. Compatibility means matching the documented, supported policy—not
emulating PHP_CodeSniffer internals.

## Compatibility matrix

The matrix tracks CakePHP CodeSniffer 5.3.x and the CakePHP 5.x framework
ruleset. Every completed custom rule has a corpus fixture.

| CakePHP concern | Mago implementation | Status |
| --- | --- | --- |
| PSR-12 whitespace, braces, imports, commas and simple strings | `mago.cakephp.toml` formatter profile | validated by CakePHP 5.x corpus |
| Short tags, silenced errors, assignments in conditions, redundant parentheses/final/use, closing tags | Native Mago rules | validated by CakePHP 5.x corpus |
| Trait names end in `Trait` | `mago-cakephp/trait-suffix` | corpus-covered |
| Public non-magic methods do not start with `_` | `mago-cakephp/public-method-underscore` | corpus-covered |
| `elseif`, never `else if` | `mago-cakephp/elseif` with safe edit | corpus-covered |
| Function/method docblocks outside tests | `mago-cakephp/function-docblock` | corpus-covered |
| PHPDoc tag alignment and type ordering | Dedicated extension rules over Mago's docblock trivia | planned |
| Control-structure bodies use braces | Native Mago `block-statement` | corpus-covered |
| `@inheritDoc`, function/method docblock alignment, tag spacing and `@throws` completeness | `mago-cakephp` docblock rules | corpus-covered |
| Chaining methods documented `@return $this` omit native return types | `mago-cakephp/return-type-docblock` | corpus-covered |
| CakePHP 5.x filename-to-type roots and return-type BC exception | CakePHP 5.x compatibility suite | planned |
| Full PHPCS/Slevomat parity | Rule-by-rule migration | tracked; no implicit equivalence claim |

The CakePHP 5.x checkout is tested on every pull request; a scheduled job also
tests the current 5.x head for drift.

## Development validation

Run `composer test`, `composer run validate-extension`, and
`scripts/check-cakephp-5.sh`. The latter accepts an optional existing shallow
CakePHP 5.x checkout, which keeps iterative checks fast:

```sh
scripts/check-cakephp-5.sh /tmp/cakephp-5
```
