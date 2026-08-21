<?php

declare(strict_types=1);

// @mago-expect lint:mago-cakephp/function-docblock
function missingFunctionDocblock(): void
{
}

/**
 * A documented function.
 */
function documentedFunction(): void
{
}

abstract class FunctionDocblock
{
    // @mago-expect lint:mago-cakephp/function-docblock
    abstract public function missingMethodDocblock(): void;

    /**
     * A documented method with an attached attribute.
     */
    #[\Deprecated]
    public function documentedMethod(): void
    {
    }
}
