<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * MaybeMonad - Encapsulates the type of an undefined value.
 */
class MaybeMonad
{
    protected $value;
    
    /**
     * __construct
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    /**
     * unit
     *
     * @param  mixed $value
     * @return MaybeMonad
     */
    public static function unit($value)
    {
        return new MaybeMonad($value);
    }
    
    /**
     * bind
     *
     * @param  callable $f
     * @param  mixed $args
     * @return MaybeMonad
     */
    public function bind(callable $f, ...$args)
    {
        if (is_null($this->value)) {
            return new MaybeMonad(null);
        } elseif (
            $this->value instanceof IdentityMonad ||
            $this->value instanceof MaybeMonad    ||
            $this->value instanceof ListMonad
        ) {
            return new MaybeMonad(
                $this->value->unit($this->value->bind($f, ...$args)));
        } else {
            return new MaybeMonad($f($this->value, ...$args));
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