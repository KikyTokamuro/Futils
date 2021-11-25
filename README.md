# Futils
Functional utils

### Examples:

#### All
Determines whether all elements of the array satisfies the predicate.
```php
Futils\all(fn($x) => $x > 100)([101, 102, 103]) // => true
```

#### Always
Creates a function that always returns a given value.
```php
Futils\always(true)() // => true
```

#### Any
Determines whether any element of the array satisfies the predicate.
```php
Futils\any(fn($x) => $x == 100)([1, 2, 100]) // => true
```

#### Compose
Function composition (fn -> ... -> f2 -> f1).
```php
Futils\compose(fn($x) => $x + 1, fn($x) => $x * 100)(2) // => 201
```

#### Contains
Check whether a value is contained in a array.
```php
Futils\contains(1337)([1, 1337, 2]) // => true
```

#### Difference
Computes the difference of arrays.
```php
Futils\difference([1, 2, 3, 4])([1, 2]) // => [3, 4]
```

#### Drop
Drops the first n elements off the front of the array.
```php
Futils\drop(2)([1, 2, 3, 4]) // => [3, 4]
```

#### Filter
Filters elements of an array using a callback function.
```php
Futils\filter(fn($x) => $x > 0)([-1, -2, 1, 2]) // => [1, 2]
```

#### Find
Find first element of the array satisfies the predicate.
```php
Futils\find(fn($x) => $x > 10)([1, 2, 11]) // => 11
```

#### Flatten
Flattens nested arrays.
```php
Futils\flatten([1, [2, [3, [4]]]]) // => [1, 2, 3, 4]
```

#### Has
Check if exists element with this key in array.
```php
Futils\has("test")(["test" => 1]) // => true
```

#### Head
Get head of array.
```php
Futils\head([1, 2, 3]) // => 1
```

#### IndexOf
Get first index of value in array.
```php
Futils\indexOf("test")([1, "test", 3]) // => 1
```

#### Join
Join array elements with a string.
```php
Futils\join([1, 2, 3])("|") // => "1|2|3"
```

#### Last
Get last element of array.
```php
Futils\last([1, 2, 3, 4]) // => 4
```

#### Map
Applying function to each element of array.
```php
Futils\map(fn($x) => $x + 1)([1, 2, 3]) // => [2, 3, 4]
```

#### Merge
Merge two arrays.
```php
Futils\merge([1, 2])([3, 4]) // => [1, 2, 3, 4]
```

#### Partial
Create partial function.
```php
Futils\partial(fn($x, $y, $z) => $x + $y + $z)(1, 2)(3) // => 6
```

#### Partition
Equivalent to [(filter f, arr), (reject f, arr)]
```php
Futils\partition(fn($x) => $x > 0)([-1, -2, 1, 2]) // => [[1, 2], [-1, -2]]
```

#### Pipe
Function composition (f1 -> f2 -> ... -> fn).
```php
Futils\pipe(fn($x) => $x + 1, fn($x) => $x * 100)(1) // => 200
```

#### Reduce
Reduce the array to a single value using a callback function.
```php
Futils\reduce(fn($x, $y) => $x + $y)([1, 2, 3]) // => 6
```

#### Reject
Like filter, but the new array is composed of all the items which fail the function.
```php
Futils\reject(fn($x) => $x > 0)([-1, -2, 1, 2]) // => [-1, -2]
```

#### Replace
Replaces elements from passed arrays into the first array.
```php
Futils\replace([1, 2, 3])([1 => 3, 2 => 2]) // => [1, 3, 2]
```

#### Tail
Get tail of array
```php
Futils\tail([1, 2, 3, 4, 5]) // => [2, 3, 4, 5]
```

#### Take
Get n first elements from array.
```php
Futils\take(2)([1, 2, 3, 4]) // => [1, 2]
```

#### When
Only when predicate is true then appling function to argument, else return argument.
```php
Futils\when(fn($x) => $x > 100)(fn($x) => $x + 5)(1000) // => 1005
```

#### Zip
Zips together its two arguments into a array of arrays.
```php
Futils\zip([1, 2, 3])([4, 5, 6]) // => [[1, 4], [2, 5], [3, 6]]
```