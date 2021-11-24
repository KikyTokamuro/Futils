<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * reduce - Reduce the array to a single value using a callback function
 *
 * @param  mixed $f
 * @return Closure
 */
function reduce(callable $f): \Closure
{
    return fn(array $arr, $init = false) =>
        array_reduce($arr, $f, $init); 
}