<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * compose - Function composition (fn -> ... -> f2 -> f1).
 *
 * @param  mixed $fns
 * @return Closure
 */
function compose(callable ...$fns): \Closure
{
    return pipe(...array_reverse($fns));
}