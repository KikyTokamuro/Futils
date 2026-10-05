<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils\Monad;

/**
 * IdentityMonad - Just annotates plain values and functions to satisfy the monad laws.
 */
class IdentityMonad implements MonadInterface
{
    protected mixed $value;

    /**
     * __construct
     *
     * @param  mixed $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * unit
     *
     * @param  mixed $value
     * @return IdentityMonad
     */
    public static function unit(mixed $value): self
    {
        return new IdentityMonad($value);
    }

    /**
     * bind
     *
     * @param  callable $f
     * @param  mixed ...$args
     * @return IdentityMonad
     */
    public function bind(callable $f, ...$args): self
    {
        if (
            $this->value instanceof IdentityMonad ||
            $this->value instanceof MaybeMonad    ||
            $this->value instanceof ListMonad
        ) {
            return new IdentityMonad(
                $this->value->unit($this->value->bind($f, ...$args)));
        } else {
            return new IdentityMonad($f($this->value, ...$args));
        }
    }

    /**
     * extract
     *
     * @return mixed
     */
    public function extract(): mixed
    {
        if (
            $this->value instanceof IdentityMonad ||
            $this->value instanceof MaybeMonad    ||
            $this->value instanceof ListMonad
        ) {
            return $this->value->extract();
        } else {
            return $this->value;
        }
    }
}
