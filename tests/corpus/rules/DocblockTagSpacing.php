<?php

declare(strict_types=1);

final class DocblockTagSpacing
{
    // @mago-expect lint:mago-cakephp/docblock-tag-spacing
    /**
     * @param  string $value
     * @return string
     */
    public function doubledSpace(string $value): string
    {
        return $value;
    }

    // @mago-expect lint:mago-cakephp/docblock-tag-spacing
    /**
     * @return	string
     */
    public function tab(): string
    {
        return 'value';
    }

    /**
     * @deprecated
     */
    public function tagWithoutValue(): void
    {
    }
}
