<?php

use PHPUnit\Framework\TestCase;
use Kikytokamuro\Futils\Func;

class FuncTest extends TestCase
{
    public function testAll(): void
    {
        $this->assertEquals(Func::all(fn($x) => $x > 100)([101, 102, 103]), true);
    }

    public function testAllEmpty(): void
    {
        $this->assertEquals(Func::all(fn($x) => $x > 100)([]), true);
    }

    public function testAlways(): void
    {
        $this->assertEquals(Func::always(true)(), true);
    }

    public function testAny(): void
    {
        $this->assertEquals(Func::any(fn($x) => $x == 100)([1, 2, 100]), true);
    }

    public function testAnyEmpty(): void
    {
        $this->assertEquals(Func::any(fn($x) => $x == 100)([]), false);
    }

    public function testCompose(): void
    {
        $this->assertEquals(Func::compose(
            fn($x) => $x + 1,
            fn($x) => $x * 100
        )(2), 201);
    }

    public function testContains(): void
    {
        $this->assertEquals(Func::contains(1337)([1, 1337, 2]), true);
    }

    public function testContainsStrict(): void
    {
        $this->assertEquals(Func::contains("1")([1, 2, 3]), false);
    }

    public function testContainsNonStrict(): void
    {
        $this->assertEquals(Func::contains("1")([1, 2, 3], false), true);
    }

    public function testChunk(): void
    {
        $this->assertEquals(Func::chunk(2)([1, 2, 3, 4, 5]), [[1, 2], [3, 4], [5]]);
    }

    public function testChunkEmpty(): void
    {
        $this->assertEquals(Func::chunk(2)([]), []);
    }

    public function testChunkExact(): void
    {
        $this->assertEquals(Func::chunk(2)([1, 2, 3, 4]), [[1, 2], [3, 4]]);
    }

    public function testDifference(): void
    {
        $this->assertEqualsCanonicalizing(
            array_values(Func::difference([1, 2, 3, 4])([1, 2])),
            [3, 4]
        );
    }

    public function testDrop(): void
    {
        $this->assertEquals(Func::drop(2)([1, 2, 3, 4]), [3, 4]);
    }

    public function testFilter(): void
    {
        $this->assertEqualsCanonicalizing(
            array_values(Func::filter(fn($x) => $x > 0)([-1, -2, 1, 2])),
            [1, 2]
        );
    }

    public function testFind(): void
    {
        $this->assertEquals(Func::find(fn($x) => $x > 10)([1, 2, 11]), 11);
    }

    public function testFindNotFound(): void
    {
        $this->assertNull(Func::find(fn($x) => $x > 100)([1, 2, 3]));
    }

    public function testFindEmpty(): void
    {
        $this->assertNull(Func::find(fn($x) => $x > 100)([]));
    }

    public function testFlatten(): void
    {
        $this->assertEquals(Func::flatten([1, [2, [3, [4]]]]), [1, 2, 3, 4]);
    }

    public function testFlattenEmpty(): void
    {
        $this->assertEquals(Func::flatten([]), []);
    }

    public function testHas(): void
    {
        $this->assertEquals(Func::has("test")(["test" => 1]), true);
    }

    public function testHasNullValue(): void
    {
        $this->assertEquals(Func::has("test")(["test" => null]), true);
    }

    public function testHasNotExists(): void
    {
        $this->assertEquals(Func::has("test")(["other" => 1]), false);
    }

    public function testHead(): void
    {
        $this->assertEquals(Func::head([1, 2, 3]), 1);
    }

    public function testHeadEmpty(): void
    {
        $this->assertNull(Func::head([]));
    }

    public function testIndexOf(): void
    {
        $this->assertEquals(Func::indexOf("test")([1, "test", 3]), 1);
    }

    public function testIndexOfNotFound(): void
    {
        $this->assertNull(Func::indexOf("missing")([1, "test", 3]));
    }

    public function testIntersection(): void
    {
        $this->assertEqualsCanonicalizing(
            array_values(Func::intersection([1, 2, 3, 4])([2, 3, 5])),
            [2, 3]
        );
    }

    public function testIntersectionEmpty(): void
    {
        $this->assertEquals(Func::intersection([1, 2])([3, 4]), []);
    }

    public function testJoin(): void
    {
        $this->assertEquals(Func::join([1, 2, 3])("|"), "1|2|3");
    }

    public function testLast(): void
    {
        $this->assertEquals(Func::last([1, 2, 3, 4]), 4);
    }

    public function testLastEmpty(): void
    {
        $this->assertNull(Func::last([]));
    }

    public function testTail(): void
    {
        $this->assertEquals(Func::tail([1, 2, 3, 4, 5]), [2, 3, 4, 5]);
    }

    public function testMap(): void
    {
        $this->assertEquals(Func::map(fn($x) => $x + 1)([1, 2, 3]), [2, 3, 4]);
    }

    public function testMerge(): void
    {
        $this->assertEquals(Func::merge([1, 2])([3, 4]), [1, 2, 3, 4]);
    }

    public function testOmit(): void
    {
        $this->assertEquals(
            Func::omit(['b'])(['a' => 1, 'b' => 2, 'c' => 3]),
            ['a' => 1, 'c' => 3]
        );
    }

    public function testOmitMultipleKeys(): void
    {
        $this->assertEquals(
            Func::omit(['a', 'c'])(['a' => 1, 'b' => 2, 'c' => 3]),
            ['b' => 2]
        );
    }

    public function testOmitNonExistentKey(): void
    {
        $this->assertEquals(
            Func::omit(['x'])(['a' => 1, 'b' => 2]),
            ['a' => 1, 'b' => 2]
        );
    }

    public function testPartial(): void
    {
        $this->assertEquals(Func::partial(fn($x, $y, $z) => $x + $y + $z)(1, 2)(3), 6);
    }

    public function testPick(): void
    {
        $this->assertEquals(
            Func::pick(['a', 'c'])(['a' => 1, 'b' => 2, 'c' => 3]),
            ['a' => 1, 'c' => 3]
        );
    }

    public function testPickSingleKey(): void
    {
        $this->assertEquals(
            Func::pick(['b'])(['a' => 1, 'b' => 2, 'c' => 3]),
            ['b' => 2]
        );
    }

    public function testPickNonExistentKey(): void
    {
        $this->assertEquals(
            Func::pick(['x'])(['a' => 1, 'b' => 2]),
            []
        );
    }

    public function testPartialWithFalse(): void
    {
        $this->assertEquals(Func::partial(fn($a, $b) => $a + $b)(1, false), 1);
    }

    public function testPartialWithNull(): void
    {
        $this->assertEquals(Func::partial(fn($a, $b) => $a + $b)(1, null), 1);
    }

    public function testPartialWithZero(): void
    {
        $this->assertEquals(Func::partial(fn($a, $b) => $a + $b)(1, 0), 1);
    }

    public function testPartition(): void
    {
        $this->assertEqualsCanonicalizing(
            Func::partition(fn($x) => $x > 0)([-1, -2, 1, 2]),
            [[2 => 1, 3 => 2], [-1, -2]]
        );
    }

    public function testPipe(): void
    {
        $this->assertEquals(Func::pipe(
            fn($x) => $x + 1,
            fn($x) => $x * 100
        )(1), 200);
    }

    public function testPipeEmpty(): void
    {
        $this->assertEquals(Func::pipe()(42), 42);
    }

    public function testReduce(): void
    {
        $this->assertEquals(Func::reduce(fn($x, $y) => $x + $y)([1, 2, 3]), 6);
    }

    public function testReduceWithInit(): void
    {
        $this->assertEquals(Func::reduce(fn($x, $y) => $x + $y)([1, 2, 3], 10), 16);
    }

    public function testReject(): void
    {
        $this->assertEqualsCanonicalizing(Func::reject(fn($x) => $x > 0)([-1, -2, 1, 2]), [-1, -2]);
    }

    public function testReplace(): void
    {
        $this->assertEquals(Func::replace([1, 2, 3])([1 => 3, 2 => 2]), [1, 3, 2]);
    }

    public function testTake(): void
    {
        $this->assertEquals(Func::take(2)([1, 2, 3, 4]), [1, 2]);
    }

    public function testUnion(): void
    {
        $this->assertEqualsCanonicalizing(
            array_values(Func::union([1, 2, 3])([3, 4, 5])),
            [1, 2, 3, 4, 5]
        );
    }

    public function testUnionEmpty(): void
    {
        $this->assertEquals(Func::union([])([]), []);
    }

    public function testUnionNoOverlap(): void
    {
        $this->assertEqualsCanonicalizing(
            array_values(Func::union([1, 2])([3, 4])),
            [1, 2, 3, 4]
        );
    }

    public function testWhen(): void
    {
        $this->assertEquals(Func::when(fn($x) => $x > 100)(fn($x) => $x + 5)(1000), 1005);
    }

    public function testWhenFalse(): void
    {
        $this->assertEquals(Func::when(fn($x) => $x > 100)(fn($x) => $x + 5)(50), 50);
    }

    public function testZip(): void
    {
        $this->assertEquals(Func::zip([1, 2, 3])([4, 5, 6]), [[1, 4], [2, 5], [3, 6]]);
    }

    public function testZipDifferentLengths(): void
    {
        $this->assertEquals(Func::zip([1, 2, 3])([4, 5]), [[1, 4], [2, 5]]);
        $this->assertEquals(Func::zip([1, 2])([4, 5, 6]), [[1, 4], [2, 5]]);
    }
}
