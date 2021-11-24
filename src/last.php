<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * last - Get last element of array.
 *
 * @param  mixed $arr
 * @return void
 */
function last(array $arr)
{
    if (count($arr) == 0) {
        return null;
    }

    if (count($arr) == 1) {
        return head($arr);
    }

    return last(tail($arr));
}