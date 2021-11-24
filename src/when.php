<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * when - Only when predicate is true then appling function to argument, else return argument.
 *
 * @param  mixed $predicate
 * @return Closure
 */
function when(callable $predicate): \Closure
{
    return fn(callable $f) =>
        fn($value) => 
            $predicate($value) ? $f($value) : $value;
}