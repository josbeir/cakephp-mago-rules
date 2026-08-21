<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Syntax\NodeKind;
use MagoCakePHP\Linter\PhpcsSuppression;

final class PublicUnderscoreMethodRule implements Rule
{
    /**
     * Describes the rule for Mago registration.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/public-method-underscore',
            name: 'CakePHP public method naming',
            description: 'Disallows an underscore prefix on public non-magic methods.',
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
        if (PhpcsSuppression::isSuppressed($context->file, $context->node->span)) {
            return;
        }

        if (preg_match('/\bpublic\s+(?:static\s+)?function\s+(_[A-Za-z0-9_]+)/', $context->getText(), $matches) !== 1) {
            return;
        }

        if (str_starts_with($matches[1], '__')) {
            return;
        }

        $context->report(Issue::new(
            sprintf('Public method name "%s" must not be prefixed with underscore.', $matches[1]),
            $context->node->span,
        ));
    }
}
