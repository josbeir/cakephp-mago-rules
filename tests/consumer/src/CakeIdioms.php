<?php

declare(strict_types=1);

namespace App;

final class CakeIdioms
{
    /**
     * Demonstrates language constructs deliberately permitted by the preset.
     *
     * @param array<string, mixed> $data Input data.
     */
    public function hasExpectedValue(array $data, mixed $expected): bool
    {
        return isset($data['value']) && !empty($data['value']) && $data['value'] == $expected;
    }

    /**
     * Demonstrates positional literal arguments permitted by the preset.
     */
    public function normalize(string $value): string
    {
        return str_replace('_', '-', $value);
    }
}
