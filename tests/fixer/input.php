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
}

if ($first) {
    runFirst();
} else if ($second) {
    runSecond();
}
