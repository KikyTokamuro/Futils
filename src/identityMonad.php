<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * IdentityMonad - Just annotates plain values and functions to satisfy the monad laws.
 */
class IdentityMonad {
    protected $value;
    
    /**
     * __construct
     *
     * @param  mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    /**
     * unit
     *
     * @param  mixed $value
     * @return IndentityMonad
     */
    public static function unit($value)
    {
        return new IdentityMonad($value);
    }
    
    /**
     * bind
     *
     * @param  callable $f
     * @param  mixed $args
     * @return IndentityMonad
     */
    public function bind(callable $f, ...$args)
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
    public function extract()
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