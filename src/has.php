<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * has - Check if exists element with this key in array.
 *
 * @param  mixed $value
 * @return Closure
 */
function has($value): \Closure
{
    return fn(array $arr) => isset($arr[$value]);
}