<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Syntax\NodeKind;
use MagoCakePHP\Linter\Docblock;

final class ThrowsTagRule implements Rule
{
    /**
     * Describes the CakePHP throws-tag rule.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/throws-tag',
            name: 'CakePHP throws tag',
            description: 'Requires every @throws tag to declare an exception type.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::Function, NodeKind::Method],
        );
    }

    /**
     * Reports throws tags without an exception type.
     */
    public function lint(LintContext $context): void
    {
        $docblock = Docblock::forDeclaration($context->file, $context->node->span);
        if ($docblock === null) {
            return;
        }

        foreach ($docblock->tags as $tag) {
            if (strcasecmp($tag->name, '@throws') !== 0 || $tag->value !== '') {
                continue;
            }

            $context->report(Issue::new('@throws must include an exception type.', $tag->nameSpan));
        }
    }
}
