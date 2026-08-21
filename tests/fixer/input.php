<?php

declare(strict_types = 1);

final class Rules
{
    /**
     * @return  $this
     */
    public function chain(): self
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function inherited(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function completelyInherited(): void
    {
    }
}

if ($first) {
    runFirst();
} else if ($second) {
    runSecond();
}
