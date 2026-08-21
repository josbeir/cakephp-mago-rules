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
use MagoCakePHP\Linter\Docblock;

final class ReturnTypeDocblockRule implements Rule
{
    /**
     * Describes the rule for Mago registration.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/return-type-docblock',
            name: 'CakePHP return type docblock',
            description: 'Prevents native return types on methods documented as @return $this.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::Method],
        );
    }

    /**
     * Reports chaining methods that declare a native return type.
     */
    public function lint(LintContext $context): void
    {
        $docblock = Docblock::forDeclaration($context->file, $context->node->span);
        if ($docblock === null || preg_match('/@return\s+\$this\b/', $context->file->getText($docblock->span)) !== 1) {
            return;
        }
        $text = $context->getText();
        if (!str_contains($text, '{')) {
            return;
        }
        if (
            preg_match(
                '/\A\s*(?:(?:public|protected|private|static|abstract|final|readonly)\s+)*function\s+[^\(]+\([^\)]*\)\s*:\s*self\b/',
                $text,
                $match,
                PREG_OFFSET_CAPTURE,
            ) !== 1
        ) {
            return;
        }
        $start = $context->node->span->start + $match[0][1] + strpos($match[0][0], ':');
        $span = new Span($start, $start + strlen($match[0][0]) - strpos($match[0][0], ':'));
        $context->report(Issue::new(
            'Chaining methods documented as @return $this must not declare a native return type.',
            $span,
        )->withEdit(TextEdit::replace($span, '')));
    }
}
