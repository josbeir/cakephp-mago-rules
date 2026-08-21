<?php

declare(strict_types=1);

final class InheritDoc
{
    // @mago-expect lint:mago-cakephp/inherit-doc
    /**
     * @inheritdoc
     */
    public function capitalization(): void
    {
    }

    // @mago-expect lint:mago-cakephp/inherit-doc
    /**
     * {@inheritdoc}
     */
    public function wrappedCompleteInheritance(): void
    {
    }

    // @mago-expect lint:mago-cakephp/inherit-doc
    /**
     * @inheritDoc
     *
     * Additional content is not allowed.
     */
    public function exclusiveTag(): void
    {
    }

    // @mago-expect lint:mago-cakephp/inherit-doc
    /**
     * Description before {@inheritDoc} is not allowed.
     */
    public function inlineTagPlacement(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function completeInheritance(): void
    {
    }

    /**
     * {@inheritDoc} Additional details.
     */
    public function partialInheritance(): void
    {
    }
}
