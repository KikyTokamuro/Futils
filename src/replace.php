<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * replace - Replaces elements from passed arrays into the first array.
 *
 * @param  mixed $arr
 * @return Closure
 */
function replace(array $arr): \Closure
{
    return fn(array $arr2, array ...$args) =>
        array_replace($arr, $arr2, ...$args);
}