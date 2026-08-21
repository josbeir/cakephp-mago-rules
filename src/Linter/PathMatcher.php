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
            $expression = preg_quote(str: $pattern, delimiter: '~');
            $expression = str_replace(search: ['\\*\\*', '\\*'], replace: ['.*', '[^/]*'], subject: $expression);
            if (preg_match('~^' . $expression . '$~D', str_replace(search: '\\', replace: '/', subject: $path)) === 1) {
                return true;
            }
        }

        return false;
    }
}
