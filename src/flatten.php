<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * flatten - Flattens nested arrays.
 *
 * @param  mixed $arr
 * @return array
 */
function flatten(array $arr): array
{
    return array_reduce($arr, fn($prev, $x) => 
        is_array($x) ? array_merge($prev, flatten($x)) : array_merge($prev, [$x]), []);
}