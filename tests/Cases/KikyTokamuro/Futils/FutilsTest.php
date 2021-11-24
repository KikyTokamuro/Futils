<?php

use PHPUnit\Framework\TestCase;
use Kikytokamuro\Futils;

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

    public function testContains(): void
    {
        $this->assertEquals(Futils\contains(1337)([1, 1337, 2]), true);
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
}