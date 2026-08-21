<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter;

use Mago\Sdk\Span;
use Mago\Sdk\Syntax\SourceFile;
use Mago\Sdk\Syntax\Trivia;
use Mago\Sdk\Syntax\TriviaKind;

final class Docblock
{
    /**
     * Finds the docblock immediately preceding a declaration.
     */
    public static function forDeclaration(SourceFile $file, Span $span): ?Trivia
    {
        $closest = null;
        foreach ($file->getTrivia() as $trivia) {
            if ($trivia->kind !== TriviaKind::DocBlockComment || $trivia->span->end > $span->start) {
                continue;
            }
            if ($closest === null || $trivia->span->end > $closest->span->end) {
                $closest = $trivia;
            }
        }

        if ($closest === null) {
            return null;
        }

        $between = $file->getText(new Span($closest->span->end, $span->start));
        $between = preg_replace('/#\[.*?\]/s', '', $between) ?? $between;

        return trim($between) === '' ? $closest : null;
    }
}
