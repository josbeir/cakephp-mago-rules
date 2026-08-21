<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Syntax\NodeKind;

final class TraitSuffixRule implements Rule
{
    /**
     * Describes the rule for Mago registration.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/trait-suffix',
            name: 'CakePHP trait suffix',
            description: 'Requires trait names to end with Trait.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::Trait],
        );
    }

    /**
     * Reports traits without the CakePHP suffix.
     */
    public function lint(LintContext $context): void
    {
        $nameNode = $context->file->getFirstDescendant(
            $context->node,
            NodeKind::LocalIdentifier,
        ) ?? $context->file->getFirstDescendant($context->node, NodeKind::Identifier);
        if ($nameNode === null) {
            return;
        }

        $name = $context->file->getText($nameNode);
        if (str_ends_with($name, 'Trait')) {
            return;
        }

        $context->report(Issue::new('Traits must have a "Trait" suffix.', $nameNode->span));
    }
}
