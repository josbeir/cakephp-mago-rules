<?php

declare(strict_types=1);

final class ThrowsTag
{
    // @mago-expect lint:mago-cakephp/throws-tag
    /**
     * @throws
     */
    public function missingType(): void
    {
    }

    /**
     * @throws \RuntimeException
     */
    public function typeWithoutDescription(): void
    {
    }

    /**
     * @throws \RuntimeException When processing fails.
     */
    public function completeTag(): void
    {
    }
}
