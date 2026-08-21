<?php

declare(strict_types=1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Syntax\NodeKind;

final class PublicUnderscoreMethodRule implements Rule
{
    /**
     * Describes the rule for Mago registration.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/public-method-underscore',
            name: 'CakePHP method naming',
            description: 'Disallows underscore-prefixed methods except CakePHP entity accessors and mutators.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::Method],
        );
    }

    /**
     * Reports public non-magic methods that start with an underscore.
     */
    public function lint(LintContext $context): void
    {
        $isPublic = true;
        foreach ($context->getChildren() as $child) {
            if ($child->kind !== NodeKind::Modifier) {
                continue;
            }

            $modifier = strtolower($context->file->getText($child));
            if (!in_array($modifier, ['public', 'protected', 'private'], strict: true)) {
                continue;
            }

            $isPublic = $modifier === 'public';
            break;
        }

        $nameNode = $context->file->getFirstDescendant(
            $context->node,
            NodeKind::LocalIdentifier,
        ) ?? $context->file->getFirstDescendant($context->node, NodeKind::Identifier);
        if ($nameNode === null) {
            return;
        }
        $name = $context->file->getText($nameNode);

        $magicMethods = [
            '__construct',
            '__destruct',
            '__call',
            '__callStatic',
            '__debugInfo',
            '__get',
            '__set',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__serialize',
            '__unserialize',
            '__toString',
            '__set_state',
            '__clone',
            '__invoke',
        ];
        if (!str_starts_with($name, '_') || in_array(needle: $name, haystack: $magicMethods, strict: true)) {
            return;
        }

        if (!$isPublic && preg_match('/^_(?:get|set)[A-Z]/', $name) === 1) {
            return;
        }

        $context->report(Issue::new(
            $isPublic
                ? sprintf('Public method name "%s" must not be prefixed with underscore.', $name)
                : sprintf('Non-public method name "%s" should not be prefixed with underscore.', $name),
            $nameNode->span,
        ));
    }
}
