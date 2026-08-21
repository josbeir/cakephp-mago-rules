<?php

declare(strict_types = 1);

namespace MagoCakePHP;

use Mago\Sdk\Extension;
use MagoCakePHP\Linter\Rules\ElseIfDeclarationRule;
use MagoCakePHP\Linter\Rules\FunctionDocblockRule;
use MagoCakePHP\Linter\Rules\PublicUnderscoreMethodRule;
use MagoCakePHP\Linter\Rules\TraitSuffixRule;

final class CakePhpExtension
{
    /**
     * Prevents instantiation of the extension factory.
     */
    private function __construct()
    {
    }

    /**
     * Creates the CakePHP compatibility extension.
     */
    public static function create(?CakePhpOptions $options = null): Extension
    {
        $options ??= new CakePhpOptions();

        return new Extension(
            identifier: 'mago-cakephp/codesniffer',
            name: 'CakePHP CodeSniffer compatibility',
            version: '0.1.0-dev',
            linterRules: [
                new TraitSuffixRule(),
                new PublicUnderscoreMethodRule(),
                new ElseIfDeclarationRule(),
                new FunctionDocblockRule($options),
            ],
        );
    }
}
