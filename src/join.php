<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * join - Join array elements with a string.
 *
 * @param  mixed $arr
 * @return Closure
 */
function join(array $arr): \Closure
{
    return fn(string $separator) => implode($separator, $arr); 
}