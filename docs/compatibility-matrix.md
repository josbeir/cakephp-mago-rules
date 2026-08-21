# Compatibility matrix

The matrix tracks CakePHP CodeSniffer 5.3.x and CakePHP 5.x's `phpcs.xml`.
Every entry must be covered by a corpus fixture before it is marked complete.

| CakePHP concern | Mago implementation | Status |
| --- | --- | --- |
| PSR-12 whitespace, braces, imports, commas and simple strings | `mago.cakephp.toml` formatter profile | validated by CakePHP 5.x corpus |
| Short tags, silenced errors, assignments in conditions, redundant parentheses/final/use, closing tags | Native Mago rules | validated by CakePHP 5.x corpus |
| Trait names end in `Trait` | `mago-cakephp/trait-suffix` | corpus-covered |
| Public non-magic methods do not start with `_` | `mago-cakephp/public-method-underscore` | corpus-covered |
| `elseif`, never `else if` | `mago-cakephp/elseif` with safe edit | corpus-covered |
| Function/method docblocks outside tests | `mago-cakephp/function-docblock` | corpus-covered |
| PHPDoc tag alignment and type ordering | Dedicated extension rules over Mago's docblock trivia | planned next |
| CakePHP control-structure body rules | AST-backed extension rules | planned next |
| CakePHP 5.x filename-to-type roots and return-type BC exception | CakePHP 5.x compatibility suite | planned next |
| Full PHPCS/Slevomat parity | Rule-by-rule migration | tracked; no implicit equivalence claim |

## Validation policy

The pinned CakePHP 5.x checkout is tested on every pull request. A scheduled
job also tests the current 5.x head and reports drift. Mago output must pass
CakePHP PHPCS except for an explicit row in this matrix; byte-identical PHPCBF
output is not required.

Mago's ordinary `lint` command intentionally enables additional quality and
security rules. The compatibility command uses an explicit native/external
allow-list so it does not claim that those additional diagnostics are CakePHP
CodeSniffer behavior.
