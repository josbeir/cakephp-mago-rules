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
    public function _hidden(): void
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

    // @mago-expect lint:mago-cakephp/return-type-docblock
    /**
     * @return $this
     */
    public function chaining(): self
    {
        return $this;
    }

    // @mago-expect lint:mago-cakephp/function-docblock
    /**
     * @return  string
     */
    public function tagSpacing(): string
    {
        return 'ok';
    }

    // @mago-expect lint:mago-cakephp/docblock-alignment
      /**
       * Returns a value.
       */
    public function misaligned(): void
    {
    }
}

if ($first) {
}

// @mago-expect lint:mago-cakephp/elseif
else if ($second) {
}

// @mago-expect lint:block-statement
if ($third)
    $third = false;
