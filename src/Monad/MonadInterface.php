<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils\Monad;

/**
 * MonadInterface - Common interface for all monads.
 */
interface MonadInterface
{
    /**
     * unit - Wrap a value in a monad.
     *
     * @param  mixed $value
     * @return self
     */
    public static function unit($value): self;

    /**
     * bind - Chain operations on the monad.
     *
     * @param  callable $f
     * @param  mixed ...$args
     * @return self
     */
    public function bind(callable $f, ...$args): self;

    /**
     * extract - Extract the value from the monad.
     *
     * @return mixed
     */
    public function extract(): mixed;
}
