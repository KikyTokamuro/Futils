<?php

declare(strict_types=1);

namespace Kikytokamuro\Futils;

/**
 * Func - Functional utilities.
 */
class Func
{
    /**
     * all - Determines whether all elements of the array satisfies the predicate.
     */
    public static function all(callable $predicate): \Closure
    {
        return fn(array $arr) =>
            array_reduce($arr, fn(bool $prev, $x) => $prev && $predicate($x), true);
    }

    /**
     * always - Creates a function that always returns a given value.
     */
    public static function always($value): \Closure
    {
        return fn() => $value;
    }

    /**
     * any - Determines whether any element of the array satisfies the predicate.
     */
    public static function any(callable $predicate): \Closure
    {
        return fn(array $arr) =>
            array_reduce($arr, fn(bool $prev, $x) => $prev ?: $predicate($x), false);
    }

    /**
     * compose - Function composition (fn -> ... -> f2 -> f1).
     */
    public static function compose(callable ...$fns): \Closure
    {
        return self::pipe(...array_reverse($fns));
    }

    /**
     * contains - Check whether a value is contained in a array.
     */
    public static function contains($value): \Closure
    {
        return fn(array $arr, bool $strict = true) =>
            in_array($value, $arr, $strict);
    }

    /**
     * chunk - Split an array into chunks of the given size.
     */
    public static function chunk(int $size): \Closure
    {
        return fn(array $arr) =>
            array_chunk($arr, $size);
    }

    /**
     * difference - Computes the difference of arrays.
     */
    public static function difference(array $arr): \Closure
    {
        return fn(array $arr2) =>
            array_diff($arr, $arr2);
    }

    /**
     * drop - Drops the first n elements off the front of the array.
     */
    public static function drop(int $count): \Closure
    {
        return fn(array $arr) =>
            array_slice($arr, $count);
    }

    /**
     * filter - Filters elements of an array using a callback function.
     */
    public static function filter(callable $f): \Closure
    {
        return fn(array $arr, int $mode = 0) =>
            array_filter($arr, $f, $mode);
    }

    /**
     * find - Find first element of the array satisfies the predicate.
     */
    public static function find(callable $predicate): \Closure
    {
        return function (array $arr) use ($predicate) {
            foreach ($arr as $element) {
                if ($predicate($element)) {
                    return $element;
                }
            }
            return null;
        };
    }

    /**
     * flatten - Flattens nested arrays.
     */
    public static function flatten(array $arr): array
    {
        $result = [];
        array_walk_recursive($arr, function ($x) use (&$result) {
            $result[] = $x;
        });
        return $result;
    }

    /**
     * has - Check if exists element with this key in array.
     */
    public static function has($value): \Closure
    {
        return fn(array $arr) => array_key_exists($value, $arr);
    }

    /**
     * head - Get head of array.
     */
    public static function head(array $arr): mixed
    {
        if (count($arr) == 0) {
            return null;
        }

        return $arr[0];
    }

    /**
     * indexOf - Get first index of value in array.
     */
    public static function indexOf($value): \Closure
    {
        return fn(array $arr, bool $strict = false) =>
            ($position = array_search($value, $arr, $strict)) === false ? null : $position;
    }

    /**
     * intersection - Computes the intersection of arrays.
     */
    public static function intersection(array $arr): \Closure
    {
        return fn(array $arr2) =>
            array_intersect($arr, $arr2);
    }

    /**
     * join - Join array elements with a string.
     */
    public static function join(array $arr): \Closure
    {
        return fn(string $separator) => implode($separator, $arr);
    }

    /**
     * last - Get last element of array.
     */
    public static function last(array $arr): mixed
    {
        if (count($arr) == 0) {
            return null;
        }
        return $arr[array_key_last($arr)];
    }

    /**
     * map - Applying function to each element of array.
     */
    public static function map(callable $f): \Closure
    {
        return fn(array $arr, array ...$rest) =>
            array_map($f, $arr, $rest);
    }

    /**
     * merge - Merge two arrays.
     */
    public static function merge(array $arr): \Closure
    {
        return fn(array $arr2) =>
            array_merge($arr, $arr2);
    }

    /**
     * omit - Returns an array without the specified keys.
     */
    public static function omit(array $keys): \Closure
    {
        return fn(array $arr) =>
            array_diff_key($arr, array_flip($keys));
    }

    /**
     * partial - Create partial function.
     */
    public static function partial(callable $f, ...$args): mixed
    {
        $arity = (new \ReflectionFunction($f))
            ->getNumberOfRequiredParameters();

        return count($args) >= $arity
            ? $f(...$args)
            : fn(...$rest) => self::partial($f, ...array_merge($args, $rest));
    }

    /**
     * pick - Returns an array with only the specified keys.
     */
    public static function pick(array $keys): \Closure
    {
        return fn(array $arr) =>
            array_intersect_key($arr, array_flip($keys));
    }

    /**
     * partition - Equivalent to [(filter f, arr), (reject f, arr)].
     */
    public static function partition(callable $f): \Closure
    {
        return fn(array $arr) =>
            [self::filter($f)($arr), self::reject($f)($arr)];
    }

    /**
     * pipe - Function composition (f1 -> f2 -> ... -> fn).
     */
    public static function pipe(callable ...$fns): \Closure
    {
        return fn($payload = null) =>
            array_reduce($fns, fn($payload, callable $f) => $f($payload), $payload);
    }

    /**
     * reduce - Reduce the array to a single value using a callback function.
     */
    public static function reduce(callable $f): \Closure
    {
        return fn(array $arr, $init = null) =>
            array_reduce($arr, $f, $init);
    }

    /**
     * reject - Like filter, but the new array is composed of all the items which fail the function.
     */
    public static function reject(callable $f): \Closure
    {
        return fn(array $arr, int $mode = 0) =>
            array_filter($arr, fn($x) => !$f($x), $mode);
    }

    /**
     * replace - Replaces elements from passed arrays into the first array.
     */
    public static function replace(array $arr): \Closure
    {
        return fn(array $arr2, array ...$args) =>
            array_replace($arr, $arr2, ...$args);
    }

    /**
     * tail - Get tail of array.
     */
    public static function tail(array $arr): array
    {
        return array_slice($arr, 1);
    }

    /**
     * take - Get n first elements from array.
     */
    public static function take(int $count): \Closure
    {
        return fn(array $arr) =>
            array_slice($arr, 0, $count);
    }

    /**
     * union - Computes the union of arrays (with deduplication).
     */
    public static function union(array $arr): \Closure
    {
        return fn(array $arr2) =>
            array_unique(array_merge($arr, $arr2));
    }

    /**
     * when - Only when predicate is true then appling function to argument, else return argument.
     */
    public static function when(callable $predicate): \Closure
    {
        return fn(callable $f) =>
            fn($value) =>
                $predicate($value) ? $f($value) : $value;
    }

    /**
     * zip - Zips together its two arguments into a array of arrays.
     */
    public static function zip(array $arr): \Closure
    {
        return function (array $arr2) use ($arr): array {
            $result = [];
            $len = min(count($arr), count($arr2));

            for ($i = 0; $i < $len; $i++) {
                $result[] = [$arr[$i], $arr2[$i]];
            }

            return $result;
        };
    }
}
