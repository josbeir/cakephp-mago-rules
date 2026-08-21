<?php

declare(strict_types=1);

final class FunctionDocblockTest
{
    public function testMethodWithoutDocblockIsAllowed(): void
    {
    }
}

function testFunctionWithoutDocblockIsAllowed(): void
{
}
