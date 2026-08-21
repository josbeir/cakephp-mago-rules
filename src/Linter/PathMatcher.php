<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter;

final class PathMatcher
{
    /**
     * @param list<string> $patterns
     */
    public static function matches(string $path, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            $expression = preg_quote($pattern, '~');
            $expression = str_replace(['\\*\\*', '\\*'], ['.*', '[^/]*'], $expression);
            if (preg_match('~^' . $expression . '$~D', str_replace('\\', '/', $path)) === 1) {
                return true;
            }
        }

        return false;
    }
}
