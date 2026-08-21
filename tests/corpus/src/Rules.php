<?php

declare(strict_types=1);

// @mago-expect lint:mago-cakephp/trait-suffix
trait Example
{
}

final class Sample
{
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
}

if ($first) {
}

// @mago-expect lint:mago-cakephp/elseif
else if ($second) {
}
