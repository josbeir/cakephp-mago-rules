<?php

declare(strict_types=1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Syntax\NodeKind;
use MagoCakePHP\Linter\Docblock;
use MagoCakePHP\Linter\PathMatcher;

final class FunctionDocblockRule implements Rule
{
    /**
     * Describes the CakePHP function-docblock rule.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/function-docblock',
            name: 'CakePHP function docblock',
            description: 'Requires a directly attached docblock for functions and methods outside tests.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::Function, NodeKind::Method],
        );
    }

    /**
     * Reports declarations without an attached docblock outside tests.
     */
    public function lint(LintContext $context): void
    {
        if (PathMatcher::matches($context->file->path, ['tests/**', '**/tests/**'])) {
            return;
        }
        if (Docblock::forDeclaration($context->file, $context->node->span) !== null) {
            return;
        }

        $context->report(Issue::new('Missing doc comment for function or method.', $context->node->span));
    }
}
