<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * difference - Computes the difference of arrays.
 *
 * @param  mixed $arr
 * @return Closure
 */
function difference(array $arr): \Closure
{
    return fn(array $arr2) => 
        array_diff($arr, $arr2);
}