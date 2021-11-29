<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * ListMonad - Abstracts away the concept of a list of items.
 */
class ListMonad
{
    protected $value;
    
    /**
     * __construct
     *
     * @param  mixed $value
     */
    public function __construct($value)
    {
        if (!is_array($value) && !$value instanceof \Traversable) {
            throw new \InvalidArgumentException('Must be traversable');
        }

        $this->value = $value;
    }
    
    /**
     * unit
     *
     * @param  mixed $value
     * @return ListMonad
     */
    public static function unit($value)
    {
        return new ListMonad($value);
    }
    
    /**
     * bind
     *
     * @param  callable $f
     * @param  mixed $args
     * @return ListMonad
     */
    public function bind(callable $f, ...$args)
    {
        $result = [];

        foreach ($this->value as $value) {
            if (
                $value instanceof IdentityMonad ||
                $value instanceof MaybeMonad    ||
                $value instanceof ListMonad
            ) {
                $result[] = $value->bind($f, ...$args);
            } else {
                $result[] = $f($value, ...$args);
            }
        }

        return new ListMonad($result);
    }

    /**
     * extract
     *
     * @return array
     */
    public function extract()
    {
        $result = [];

        foreach ($this->value as $value) {
            if (
                $value instanceof IdentityMonad ||
                $value instanceof MaybeMonad    ||
                $value instanceof ListMonad 
            ) {
                $result[] = $value->extract();
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }
}