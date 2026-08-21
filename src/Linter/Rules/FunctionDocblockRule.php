<?php

declare(strict_types = 1);

namespace MagoCakePHP\Linter\Rules;

use Mago\Sdk\Linter\LintContext;
use Mago\Sdk\Linter\Rule;
use Mago\Sdk\Linter\RuleDefinition;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Syntax\NodeKind;
use Mago\Sdk\Syntax\TriviaKind;
use MagoCakePHP\CakePhpOptions;
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

        $closest = null;
        foreach ($context->file->getTrivia() as $trivia) {
            if ($trivia->kind !== TriviaKind::DocBlockComment || $trivia->span->end > $context->node->span->start) {
                continue;
            }
            if ($closest === null || $trivia->span->end > $closest->span->end) {
                $closest = $trivia;
            }
        }

        if ($closest !== null) {
            $between = $context->file->getText(new \Mago\Sdk\Span($closest->span->end, $context->node->span->start));
            // Attributes may appear between a docblock and a declaration.
            $between = preg_replace('/#\[.*?\]/s', '', $between) ?? $between;
            if (trim($between) === '') {
                return;
            }
        }

        $context->report(Issue::new('Missing doc comment for function or method.', $context->node->span));
    }
}
