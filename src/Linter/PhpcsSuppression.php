<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter;

use Mago\Sdk\Span;
use Mago\Sdk\Syntax\SourceFile;

/**
 * Honors the broad `// phpcs:disable` / `// phpcs:enable` regions used in
 * CakePHP itself while projects migrate from PHPCS to Mago.
 */
final class PhpcsSuppression
{
    /**
     * Determines whether a span lies in a broad PHPCS suppression region.
     */
    public static function isSuppressed(SourceFile $file, Span $span): bool
    {
        $before = substr($file->contents, 0, $span->start);
        $disable = strripos($before, 'phpcs:disable');
        if ($disable === false) {
            return false;
        }

        $enable = strripos($before, 'phpcs:enable');

        return $enable === false || $enable < $disable;
    }
}
