<?php

declare(strict_types = 1);

namespace MagoCakePHP;

/**
 * Intentional project-level variations in the CakePHP standard.
 *
 * Mago does not pass arbitrary TOML settings to external rules. Consumers can
 * configure these options from their project-owned worker entrypoint instead.
 */
final readonly class CakePhpOptions
{
    /**
     * @param list<string> $functionDocblockExcludes
     */
    public function __construct(
        public array $functionDocblockExcludes = ['tests/**'],
    ) {
    }
}
