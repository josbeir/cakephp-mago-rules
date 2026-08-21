<?php

declare(strict_types=1);

// @mago-expect lint:mago-cakephp/trait-suffix
trait Example
{
}

final class Sample
{
    // @mago-expect lint:mago-cakephp/inherit-doc
    /**
     * @inheritdoc
     */
    public function inherited(): void
    {
    }

    // @mago-expect lint:mago-cakephp/public-method-underscore
    // @mago-expect lint:mago-cakephp/function-docblock
    static public function _hidden(): void
    {
    }

    /**
     * Returns a value.
     */
    public function documented(): string
    {
        return 'ok';
    }

    // @mago-expect lint:mago-cakephp/function-docblock
    public function missing(): void
    {
    }

    /**
     * @return $this
     */
    public function chaining(): static
    {
        return $this;
    }

    // @mago-expect lint:mago-cakephp/docblock-tag-spacing
    /**
     * @return  string
     */
    public function tagSpacing(): string
    {
        return 'ok';
    }

    // @mago-expect lint:mago-cakephp/throws-tag
    /**
     * Throws an exception.
     *
     * @throws
     */
    public function incompleteThrows(): void
    {
    }

    /**
     * @throws \RuntimeException When the operation fails.
     */
    protected function _getAllowedProtectedMethod(): void
    {
    }

    /**
     * {@inheritDoc} Additional details are allowed after an inline tag.
     */
    public function partialInheritance(): void
    {
    }

    /**
     * Magic methods are exempt from the underscore rule.
     *
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return null;
    }
}

interface FluentInterface
{
    /**
     * @return $this
     */
    public function chain(): self;
}

if ($first) {
}

// @mago-expect lint:mago-cakephp/elseif
else if ($second) {
}

// @mago-expect lint:block-statement
if ($third)
    $third = false;
