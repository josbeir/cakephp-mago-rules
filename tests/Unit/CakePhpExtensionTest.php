<?php

declare(strict_types = 1);

namespace MagoCakePHP\Tests\Unit;

use MagoCakePHP\CakePhpExtension;
use PHPUnit\Framework\TestCase;

final class CakePhpExtensionTest extends TestCase
{
    public function testRegistersTheCakePhpCompatibilityRules(): void
    {
        $extension = CakePhpExtension::create();

        self::assertSame('mago-cakephp/codesniffer', $extension->identifier);
        self::assertSame(
            [
                'mago-cakephp/trait-suffix',
                'mago-cakephp/public-method-underscore',
                'mago-cakephp/elseif',
                'mago-cakephp/function-docblock',
            ],
            array_map(static fn($rule): string => $rule->getDefinition()->code, $extension->linterRules),
        );
    }
}
