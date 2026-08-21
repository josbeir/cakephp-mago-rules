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

Run `mago extension validate` and `mago format`. For strict CakePHP
compatibility, use an explicit lint allow-list: Mago's normal `lint` command
also enables its broader quality and security rules, which CakePHP PHPCS does
not define.

```sh
mago lint --only no-short-opening-tag,no-error-control-operator,no-assign-in-condition,no-redundant-parentheses,no-redundant-final,no-redundant-use,no-closing-tag,mago-cakephp/trait-suffix,mago-cakephp/public-method-underscore,mago-cakephp/elseif,mago-cakephp/function-docblock
```

## What this does—and does not—replace

This package is a focused CakePHP compatibility layer for Mago, not a
drop-in replacement for PHP_CodeSniffer or every CakePHP CodeSniffer sniff.
Its covered rules are listed in the
[compatibility matrix](docs/compatibility-matrix.md); anything marked
**planned** is deliberately not enforced yet.

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

## Development validation

Run `composer test`, `composer run validate-extension`, and
`scripts/check-cakephp-5.sh`. The latter accepts an optional existing shallow
CakePHP 5.x checkout, which keeps iterative checks fast:

```sh
scripts/check-cakephp-5.sh /tmp/cakephp-5
```
