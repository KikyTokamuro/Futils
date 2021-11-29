<?php

use PHPUnit\Framework\TestCase;
use Kikytokamuro\Futils;
use Kikytokamuro\Futils\IdentityMonad;
use Kikytokamuro\Futils\ListMonad;
use Kikytokamuro\Futils\MaybeMonad;

class FutilsTest extends TestCase
{
    public function testAll(): void
    {
        $this->assertEquals(Futils\all(fn($x) => $x > 100)([101, 102, 103]), true);
    }

    public function testAlways(): void
    {
        $this->assertEquals(Futils\always(true)(), true);
    }

    public function testAny(): void
    {
        $this->assertEquals(Futils\any(fn($x) => $x == 100)([1, 2, 100]), true);
    }

    public function testCompose(): void
    {
        $this->assertEquals(Futils\compose(
            fn($x) => $x + 1,
            fn($x) => $x * 100
        )(2), 201);
    }

    public function testContains(): void
    {
        $this->assertEquals(Futils\contains(1337)([1, 1337, 2]), true);
    }

    public function testDifference(): void
    {
        $this->assertEqualsCanonicalizing(Futils\difference([1, 2, 3, 4])([1, 2]), [3, 4]);
    }

    public function testDrop(): void
    {
        $this->assertEquals(Futils\drop(2)([1, 2, 3, 4]), [3, 4]);
    }

    public function testFilter(): void
    {
        $this->assertEqualsCanonicalizing(Futils\filter(fn($x) => $x > 0)([-1, -2, 1, 2]), [1, 2]);
    }

    public function testFind(): void
    {
        $this->assertEquals(Futils\find(fn($x) => $x > 10)([1, 2, 11]), 11);
    }

    public function testFlatten(): void
    {
        $this->assertEquals(Futils\flatten([1, [2, [3, [4]]]]), [1, 2, 3, 4]);
    }

    public function testHas(): void
    {
        $this->assertEquals(Futils\has("test")(["test" => 1]), true);
    }

    public function testHead(): void
    {
        $this->assertEquals(Futils\head([1, 2, 3]), 1);
    }

    public function testIndexOf(): void
    {
        $this->assertEquals(Futils\indexOf("test")([1, "test", 3]), 1);
    }

    public function testJoin(): void
    {
        $this->assertEquals(Futils\join([1, 2, 3])("|"), "1|2|3");
    }

    public function testLast(): void
    {
        $this->assertEquals(Futils\last([1, 2, 3, 4]), 4);
    }

    public function testTail(): void
    {
        $this->assertEquals(Futils\tail([1, 2, 3, 4, 5]), [2, 3, 4, 5]);
    }

    public function testMap(): void
    {
        $this->assertEquals(Futils\map(fn($x) => $x + 1)([1, 2, 3]), [2, 3, 4]);
    }

    public function testMerge(): void
    {
        $this->assertEquals(Futils\merge([1, 2])([3, 4]), [1, 2, 3, 4]);
    }

    public function testPartial(): void
    {
        $this->assertEquals(Futils\partial(fn($x, $y, $z) => $x + $y + $z)(1, 2)(3), 6);
    }

    public function testPartition(): void
    {
        $this->assertEqualsCanonicalizing(Futils\partition(fn($x) => $x > 0)([-1, -2, 1, 2]), [[1, 2], [-1, -2]]);
    }

    public function testPipe(): void
    {
        $this->assertEquals(Futils\pipe(
            fn($x) => $x + 1,
            fn($x) => $x * 100
        )(1), 200);
    }

    public function testReduce(): void
    {
        $this->assertEquals(Futils\reduce(fn($x, $y) => $x + $y)([1, 2, 3]), 6);
    }

    public function testReject(): void
    {
        $this->assertEqualsCanonicalizing(Futils\reject(fn($x) => $x > 0)([-1, -2, 1, 2]), [-1, -2]);
    }

    public function testReplace(): void
    {
        $this->assertEquals(Futils\replace([1, 2, 3])([1 => 3, 2 => 2]), [1, 3, 2]);
    }

    public function testTake(): void
    {
        $this->assertEquals(Futils\take(2)([1, 2, 3, 4]), [1, 2]);
    }

    public function testWhen(): void
    {
        $this->assertEquals(Futils\when(fn($x) => $x > 100)(fn($x) => $x + 5)(1000), 1005);
    }

    public function testZip(): void
    {
        $this->assertEquals(Futils\zip([1, 2, 3])([4, 5, 6]), [[1, 4], [2, 5], [3, 6]]);
    }

    public function testIdentityMonad(): void
    {
        $result = (new Futils\IdentityMonad(100))
            ->bind(fn($x, $n) => $x * $n, 2)
            ->bind("strval")
            ->extract();

        $this->assertEquals($result, "200");
    }

    public function testMaybeMonad(): void
    {
        $bindtest = (new Futils\MaybeMonad("test"))
            ->bind(fn() => new Futils\MaybeMonad(null))
            ->extract();
        $this->assertEquals($bindtest, null);

        $bindtest2 = (new Futils\MaybeMonad(null))
            ->bind(fn($x) => $x + 1);
        $this->assertEquals($bindtest2, new Futils\MaybeMonad(null));

        $unittest = new Futils\MaybeMonad(null);
        $this->assertEquals($unittest, $unittest->unit(null));
    }

    public function testListMonad(): void
    {
        $result = (new Futils\ListMonad([
            1, 
            new IdentityMonad(2), 
            new MaybeMonad(3), 
            new ListMonad([4])
        ]))->bind(fn($x) => $x + 100)->extract();
        $this->assertEquals($result, [101, 102, 103, [104]]);
    }
}