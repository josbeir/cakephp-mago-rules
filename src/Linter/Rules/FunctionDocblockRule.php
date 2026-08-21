<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Syntax\NodeKind;
use MagoCakePHP\CakePhpOptions;
use MagoCakePHP\Linter\Docblock;
use MagoCakePHP\Linter\PathMatcher;
use MagoCakePHP\Linter\PhpcsSuppression;

final class FunctionDocblockRule implements Rule
{
    /**
     * @param CakePhpOptions $options Project-specific path exemptions.
     */
    public function __construct(
        private readonly CakePhpOptions $options,
    ) {
    }

    /**
     * Describes the rule for Mago registration.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/function-docblock',
            name: 'CakePHP function docblock',
            description: 'Requires a directly attached docblock for functions and methods.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::Function, NodeKind::Method],
        );
    }

    /**
     * Reports declarations without a directly attached docblock.
     */
    public function lint(LintContext $context): void
    {
        if (PathMatcher::matches($context->file->path, $this->options->functionDocblockExcludes)) {
            return;
        }
        if (PhpcsSuppression::isSuppressed($context->file, $context->node->span)) {
            return;
        }

        $docblock = Docblock::forDeclaration($context->file, $context->node->span);
        if ($docblock !== null) {
            $text = $context->file->getText($docblock->span);
            if (preg_match('/@(param|return|throws)\s{2,}/', $text, $match, PREG_OFFSET_CAPTURE) === 1) {
                $start = $docblock->span->start + $match[0][1] + strlen($match[1][0]);
                $span = new \Mago\Sdk\Span($start, $start + strlen($match[0][0]) - strlen($match[1][0]));
                $context->report(Issue::new(
                    'Docblock tags must be followed by one space.',
                    $span,
                )->withEdit(\Mago\Sdk\Reporting\TextEdit::replace($span, ' ')));
            }
            if (preg_match('/@throws\s*(?:\r?\n|\*\/)/', $text, $match, PREG_OFFSET_CAPTURE) === 1) {
                $span = new \Mago\Sdk\Span(
                    $docblock->span->start + $match[0][1],
                    $docblock->span->start + $match[0][1] + strlen($match[0][0]),
                );
                $context->report(Issue::new('@throws must include an exception type and description.', $span));
            }

            return;
        }

        $context->report(Issue::new('Missing doc comment for function or method.', $context->node->span));
    }
}
