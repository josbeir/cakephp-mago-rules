<?php

declare(strict_types=1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Reporting\TextEdit;
use Mago\Sdk\Syntax\NodeKind;
use MagoCakePHP\Linter\Docblock;

final class DocblockTagSpacingRule implements Rule
{
    /**
     * Describes the CakePHP PHPDoc tag-spacing rule.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/docblock-tag-spacing',
            name: 'CakePHP docblock tag spacing',
            description: 'Requires one space between a PHPDoc tag and its value.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::Function, NodeKind::Method],
        );
    }

    /**
     * Reports tag values not separated by exactly one space.
     */
    public function lint(LintContext $context): void
    {
        $docblock = Docblock::forDeclaration($context->file, $context->node->span);
        if ($docblock === null) {
            return;
        }

        foreach ($docblock->tags as $tag) {
            if ($tag->value === '' || $tag->whitespace === ' ') {
                continue;
            }

            $context->report(Issue::new(
                'Docblock tags must be followed by one space.',
                $tag->whitespaceSpan,
            )->withEdit(TextEdit::replace($tag->whitespaceSpan, ' ')));
        }
    }
}
