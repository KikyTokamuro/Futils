# Futils
Functional utils

### Examples:

```php
use Kikytokamuro\Futils\Func;
use Kikytokamuro\Futils\Monad;
```

#### All
Determines whether all elements of the array satisfies the predicate.
```php
Func::all(fn($x) => $x > 100)([101, 102, 103]) // => true
```

#### Always
Creates a function that always returns a given value.
```php
Func::always(true)() // => true
```

#### Any
Determines whether any element of the array satisfies the predicate.
```php
Func::any(fn($x) => $x == 100)([1, 2, 100]) // => true
```

#### Compose
Function composition (fn -> ... -> f2 -> f1).
```php
Func::compose(fn($x) => $x + 1, fn($x) => $x * 100)(2) // => 201
```

#### Contains
Check whether a value is contained in a array.
```php
Func::contains(1337)([1, 1337, 2]) // => true
```

#### Chunk
Split an array into chunks of the given size.
```php
Func::chunk(2)([1, 2, 3, 4, 5]) // => [[1, 2], [3, 4], [5]]
```

#### Difference
Computes the difference of arrays.
```php
Func::difference([1, 2, 3, 4])([1, 2]) // => [3, 4]
```

#### Drop
Drops the first n elements off the front of the array.
```php
Func::drop(2)([1, 2, 3, 4]) // => [3, 4]
```

#### Filter
Filters elements of an array using a callback function.
```php
Func::filter(fn($x) => $x > 0)([-1, -2, 1, 2]) // => [1, 2]
```

#### Find
Find first element of the array satisfies the predicate.
```php
Func::find(fn($x) => $x > 10)([1, 2, 11]) // => 11
```

#### Flatten
Flattens nested arrays.
```php
Func::flatten([1, [2, [3, [4]]]]) // => [1, 2, 3, 4]
```

#### Has
Check if exists element with this key in array.
```php
Func::has("test")(["test" => 1]) // => true
```

#### Head
Get head of array.
```php
Func::head([1, 2, 3]) // => 1
```

#### IndexOf
Get first index of value in array.
```php
Func::indexOf("test")([1, "test", 3]) // => 1
```

#### Intersection
Computes the intersection of arrays.
```php
Func::intersection([1, 2, 3, 4])([2, 3, 5]) // => [2, 3]
```

#### Join
Join array elements with a string.
```php
Func::join([1, 2, 3])("|") // => "1|2|3"
```

#### Last
Get last element of array.
```php
Func::last([1, 2, 3, 4]) // => 4
```

#### Map
Applying function to each element of array.
```php
Func::map(fn($x) => $x + 1)([1, 2, 3]) // => [2, 3, 4]
```

#### Merge
Merge two arrays.
```php
Func::merge([1, 2])([3, 4]) // => [1, 2, 3, 4]
```

#### Omit
Returns an array without the specified keys.
```php
Func::omit(['b'])(['a' => 1, 'b' => 2, 'c' => 3]) // => ['a' => 1, 'c' => 3]
```

#### Partial
Create partial function.
```php
Func::partial(fn($x, $y, $z) => $x + $y + $z)(1, 2)(3) // => 6
```

#### Pick
Returns an array with only the specified keys.
```php
Func::pick(['a', 'c'])(['a' => 1, 'b' => 2, 'c' => 3]) // => ['a' => 1, 'c' => 3]
```

#### Partition
Equivalent to [(filter f, arr), (reject f, arr)]
```php
Func::partition(fn($x) => $x > 0)([-1, -2, 1, 2]) // => [[1, 2], [-1, -2]]
```

#### Pipe
Function composition (f1 -> f2 -> ... -> fn).
```php
Func::pipe(fn($x) => $x + 1, fn($x) => $x * 100)(1) // => 200
```

#### Reduce
Reduce the array to a single value using a callback function.
```php
Func::reduce(fn($x, $y) => $x + $y)([1, 2, 3]) // => 6
```

#### Reject
Like filter, but the new array is composed of all the items which fail the function.
```php
Func::reject(fn($x) => $x > 0)([-1, -2, 1, 2]) // => [-1, -2]
```

#### Replace
Replaces elements from passed arrays into the first array.
```php
Func::replace([1, 2, 3])([1 => 3, 2 => 2]) // => [1, 3, 2]
```

#### Tail
Get tail of array
```php
Func::tail([1, 2, 3, 4, 5]) // => [2, 3, 4, 5]
```

#### Take
Get n first elements from array.
```php
Func::take(2)([1, 2, 3, 4]) // => [1, 2]
```

#### Union
Computes the union of arrays (with deduplication).
```php
Func::union([1, 2, 3])([3, 4, 5]) // => [1, 2, 3, 4, 5]
```

#### When
Only when predicate is true then appling function to argument, else return argument.
```php
Func::when(fn($x) => $x > 100)(fn($x) => $x + 5)(1000) // => 1005
```

#### Zip
Zips together its two arguments into a array of arrays.
```php
Func::zip([1, 2, 3])([4, 5, 6]) // => [[1, 4], [2, 5], [3, 6]]
```

#### Monads
**IdentityMonad** - Just annotates plain values and functions to satisfy the monad laws.
```php
(new Monad\IdentityMonad(100))
    ->bind(fn($x, $n) => $x * $n, 2)
    ->bind("strval")
    ->extract() // => "200"
```

**MaybeMonad** - Encapsulates the type of an undefined value.
```php
(new Monad\MaybeMonad("test"))
    ->bind(fn() => null)
    ->bind(fn($x) => $x + 1)
    ->extract() // => null
```

**ListMonad** - Abstracts away the concept of a list of items.
```php
(new Monad\ListMonad([1, new Monad\IdentityMonad(2), new Monad\MaybeMonad(3), new Monad\ListMonad([4])]))
    ->bind(fn($x) => $x + 100)
    ->extract() // => [101, 102, 103, [104]]
```
