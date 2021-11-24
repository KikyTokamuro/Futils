<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * contains - Check whether a value is contained in a array.
 *
 * @param  mixed $value
 * @return Closure
 */
function contains($value): \Closure
{
    return fn(array $arr, bool $strict = false) =>
        in_array($value, $arr, $strict); 
}