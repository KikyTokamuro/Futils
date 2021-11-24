<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * indexOf - Get first index of value in array.
 *
 * @param  mixed $value
 * @return Closure
 */
function indexOf($value): \Closure
{
    return fn(array $arr, bool $strict = false) => 
        ($position = array_search($value, $arr, $strict)) === false ? null : $position;
}