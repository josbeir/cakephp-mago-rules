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

final class DocblockAlignmentRule implements Rule
{
    /**
     * Describes the rule for Mago registration.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/docblock-alignment',
            name: 'CakePHP docblock alignment',
            description: 'Requires function and method docblocks to align with their declaration.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::Function, NodeKind::Method],
        );
    }

    /**
     * Reports a directly attached docblock with mismatched indentation.
     */
    public function lint(LintContext $context): void
    {
        $docblock = Docblock::forDeclaration($context->file, $context->node->span);
        if ($docblock === null) {
            return;
        }
        $contents = $context->file->contents;
        $docStart = strrpos(substr($contents, 0, $docblock->span->start), "\n");
        $codeStart = strrpos(substr($contents, 0, $context->node->span->start), "\n");
        $docStart = $docStart === false ? 0 : $docStart + 1;
        $codeStart = $codeStart === false ? 0 : $codeStart + 1;
        $docIndent = strspn($contents, " \t", $docStart);
        $codeIndent = strspn($contents, " \t", $codeStart);
        if ($docIndent === $codeIndent) {
            return;
        }

        $span = new Span($docStart, $docblock->span->start);
        $context->report(Issue::new(
            'Docblock must align with its declaration.',
            $span,
        )->withEdit(TextEdit::replace($span, str_repeat(' ', $codeIndent))));
    }
}
