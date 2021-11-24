<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * merge - Merge two arrays.
 *
 * @param  mixed $arr
 * @return Closure
 */
function merge(array $arr): \Closure
{
    return fn(array $arr2) =>
        array_merge($arr, $arr2);
}