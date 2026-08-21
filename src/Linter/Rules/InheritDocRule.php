<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Reporting\TextEdit;
use Mago\Sdk\Span;
use Mago\Sdk\Syntax\NodeKind;
use Mago\Sdk\Syntax\TriviaKind;

final class InheritDocRule implements Rule
{
    /**
     * Describes the rule for Mago registration.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/inherit-doc',
            name: 'CakePHP inheritDoc',
            description: 'Enforces CakePHP @inheritDoc spelling and placement.',
            defaultLevel: Level::Warning,
            defaultEnabled: true,
            targets: [NodeKind::Program],
        );
    }

    /**
     * Validates every docblock in the source file.
     */
    public function lint(LintContext $context): void
    {
        foreach ($context->file->getTrivia() as $trivia) {
            if ($trivia->kind !== TriviaKind::DocBlockComment) {
                continue;
            }
            $text = $context->file->getText($trivia->span);
            if (preg_match('/@inheritdoc/i', $text, $match, PREG_OFFSET_CAPTURE) !== 1) {
                continue;
            }
            $span = new Span(
                $trivia->span->start + $match[0][1],
                $trivia->span->start + $match[0][1] + strlen($match[0][0]),
            );
            if ($match[0][0] !== '@inheritDoc') {
                $context->report(Issue::new(
                    '@inheritDoc must use CakePHP capitalization.',
                    $span,
                )->withEdit(TextEdit::replace($span, '@inheritDoc')));
                continue;
            }
            $content = preg_replace('/^\s*\/\*\*|\*\/\s*$/', '', $text) ?? $text;
            $content = preg_replace('/^\s*\*\s?/m', '', $content) ?? $content;
            $content = trim($content);
            if ($content === '{@inheritDoc}') {
                $context->report(Issue::new(
                    'Use @inheritDoc when inheriting the complete docblock.',
                    $span,
                )->withEdit(TextEdit::replace($span, '@inheritDoc')));
                continue;
            }
            if (str_starts_with($content, '@inheritDoc') && $content !== '@inheritDoc') {
                $context->report(Issue::new('@inheritDoc must be the only docblock content.', $span));
            }
            if (!str_starts_with($content, '{@inheritDoc}') && str_contains($content, '{@inheritDoc}')) {
                $context->report(Issue::new('{@inheritDoc} must be the first docblock content.', $span));
            }
        }
    }
}
