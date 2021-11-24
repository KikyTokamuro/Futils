<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * find - Find first element of the array satisfies the predicate.
 *
 * @param  mixed $predicate
 * @return Closure
 */
function find(callable $predicate): \Closure
{
    return function(array $arr) use ($predicate) {
        if (count($arr) == 0) {
            return null;
        }

        $element = head($arr);
        
        if ($predicate($element)) {
            return $element;
        }

        return find($predicate)(tail($arr));
    };
}