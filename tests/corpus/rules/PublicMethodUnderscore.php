<?php

declare(strict_types=1);

final class PublicMethodUnderscore
{
    // @mago-expect lint:mago-cakephp/public-method-underscore
    public function _explicitPublic(): void
    {
    }

    // @mago-expect lint:mago-cakephp/public-method-underscore
    function _implicitPublic(): void
    {
    }

    // @mago-expect lint:mago-cakephp/public-method-underscore
    protected function _protectedMethod(): void
    {
    }

    // @mago-expect lint:mago-cakephp/public-method-underscore
    private function _privateMethod(): void
    {
    }

    protected function _getProperty(): mixed
    {
        return null;
    }

    private function _setProperty(mixed $value): void
    {
    }

    public function __get(string $name): mixed
    {
        return null;
    }

    public function validMethod(): void
    {
    }
}
