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
use MagoCakePHP\Linter\PhpcsSuppression;

final class ElseIfDeclarationRule implements Rule
{
    /**
     * Describes the rule for Mago registration.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/elseif',
            name: 'CakePHP elseif declaration',
            description: 'Requires elseif instead of else if.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::IfStatementBodyElseClause],
        );
    }

    /**
     * Replaces the spaced declaration form with elseif.
     */
    public function lint(LintContext $context): void
    {
        if (preg_match('/\A\s*(else\s+if)\b/', $context->getText(), $matches, PREG_OFFSET_CAPTURE) !== 1) {
            return;
        }

        $start = $context->node->span->start + $matches[1][1];
        $span = new Span($start, $start + strlen($matches[1][0]));
        if (PhpcsSuppression::isSuppressed($context->file, $span)) {
            return;
        }

        $context->report(Issue::new(
            'Usage of ELSE IF is not allowed; use ELSEIF instead.',
            $span,
        )->withEdit(TextEdit::replace($span, 'elseif')));
    }
}
