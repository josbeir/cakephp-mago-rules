<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Reporting\TextEdit;
use Mago\Sdk\Syntax\NodeKind;
use MagoCakePHP\Linter\Docblock;

final class ChainingReturnTypeRule implements Rule
{
    /**
     * Describes the CakePHP chaining return-type rule.
     */
    public function getDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            code: 'mago-cakephp/chaining-return-type',
            name: 'CakePHP chaining return type',
            description: 'Prevents native return types on concrete methods documented as @return $this.',
            defaultLevel: Level::Error,
            defaultEnabled: true,
            targets: [NodeKind::Method],
        );
    }

    /**
     * Reports concrete chaining methods with native return types.
     */
    public function lint(LintContext $context): void
    {
        $docblock = Docblock::forDeclaration($context->file, $context->node->span);
        if ($docblock === null || !$this->returnsThis($docblock)) {
            return;
        }

        if ($context->file->getFirstDescendant($context->node, NodeKind::MethodAbstractBody) !== null) {
            return;
        }

        $returnType = null;
        foreach ($context->getChildren() as $child) {
            if ($child->kind !== NodeKind::FunctionLikeReturnTypeHint) {
                continue;
            }

            $returnType = $child;
            break;
        }
        if ($returnType === null) {
            return;
        }

        $issue = Issue::new(
            'Chaining methods documented as @return $this must not declare a native return type.',
            $returnType->span,
        );
        $type = ltrim(string: trim(string: $context->file->getText($returnType)), characters: ': ');
        if (strcasecmp($type, 'self') === 0) {
            $issue = $issue->withEdit(TextEdit::replace($returnType->span, ''));
        }

        $context->report($issue);
    }

    /**
     * Checks whether the first return tag documents fluent `$this` behavior.
     */
    private function returnsThis(Docblock $docblock): bool
    {
        foreach ($docblock->tags as $tag) {
            if (strcasecmp($tag->name, '@return') !== 0) {
                continue;
            }

            return preg_match('/^\$this(?:\s|$)/', $tag->value) === 1;
        }

        return false;
    }
}
