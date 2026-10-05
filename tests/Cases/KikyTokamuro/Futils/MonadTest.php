<?php

use PHPUnit\Framework\TestCase;
use Kikytokamuro\Futils\Monad\IdentityMonad;
use Kikytokamuro\Futils\Monad\ListMonad;
use Kikytokamuro\Futils\Monad\MaybeMonad;

class MonadTest extends TestCase
{
    public function testIdentityMonad(): void
    {
        $result = (new IdentityMonad(100))
            ->bind(fn($x, $n) => $x * $n, 2)
            ->bind("strval")
            ->extract();

        $this->assertEquals($result, "200");
    }

    public function testIdentityMonadUnit(): void
    {
        $monad = IdentityMonad::unit(42);
        $this->assertEquals(42, $monad->extract());
    }

    public function testIdentityMonadNestedIdentity(): void
    {
        $result = (new IdentityMonad(new IdentityMonad(10)))
            ->bind(fn($x) => $x * 2)
            ->extract();

        $this->assertEquals(20, $result);
    }

    public function testIdentityMonadNestedMaybe(): void
    {
        $result = (new IdentityMonad(new MaybeMonad(5)))
            ->bind(fn($x) => $x + 10)
            ->extract();

        $this->assertEquals(15, $result);
    }

    public function testIdentityMonadNestedMaybeNull(): void
    {
        $result = (new IdentityMonad(new MaybeMonad(null)))
            ->bind(fn($x) => $x + 10)
            ->extract();

        $this->assertNull($result);
    }

    public function testIdentityMonadNestedList(): void
    {
        $result = (new IdentityMonad(new ListMonad([1, 2, 3])))
            ->bind(fn($x) => $x * 2)
            ->extract();

        $this->assertEquals([2, 4, 6], $result);
    }

    public function testIdentityMonadMultipleBinds(): void
    {
        $result = (new IdentityMonad(5))
            ->bind(fn($x) => $x + 3)
            ->bind(fn($x) => $x * 2)
            ->bind(fn($x) => $x - 1)
            ->extract();

        $this->assertEquals(15, $result);
    }

    public function testIdentityMonadBindWithMultipleArgs(): void
    {
        $result = (new IdentityMonad(10))
            ->bind(fn($x, $y, $z) => $x + $y + $z, 5, 3)
            ->extract();

        $this->assertEquals(18, $result);
    }

    public function testIdentityMonadArrayValue(): void
    {
        $result = (new IdentityMonad([1, 2, 3]))
            ->bind(fn($arr) => array_map(fn($x) => $x * 2, $arr))
            ->extract();

        $this->assertEquals([2, 4, 6], $result);
    }

    public function testMaybeMonadUnit(): void
    {
        $monad = MaybeMonad::unit(42);
        $this->assertEquals(42, $monad->extract());
    }

    public function testMaybeMonadUnitNull(): void
    {
        $monad = MaybeMonad::unit(null);
        $this->assertNull($monad->extract());
    }

    public function testMaybeMonadChainedBinds(): void
    {
        $result = (new MaybeMonad(10))
            ->bind(fn($x) => $x + 5)
            ->bind(fn($x) => $x * 2)
            ->bind(fn($x) => $x - 3)
            ->extract();

        $this->assertEquals(27, $result);
    }

    public function testMaybeMonadShortCircuitOnNull(): void
    {
        $result = (new MaybeMonad(null))
            ->bind(fn($x) => $x + 5)
            ->bind(fn($x) => $x * 2)
            ->bind(fn($x) => $x - 3)
            ->extract();

        $this->assertNull($result);
    }

    public function testMaybeMonadBindReturnsNull(): void
    {
        $result = (new MaybeMonad(10))
            ->bind(fn($x) => null)
            ->bind(fn($x) => $x + 5)
            ->extract();

        $this->assertNull($result);
    }

    public function testMaybeMonadNestedIdentity(): void
    {
        $result = (new MaybeMonad(new IdentityMonad(5)))
            ->bind(fn($x) => $x + 10)
            ->extract();

        $this->assertEquals(15, $result);
    }

    public function testMaybeMonadNestedMaybe(): void
    {
        $result = (new MaybeMonad(new MaybeMonad(5)))
            ->bind(fn($x) => $x + 10)
            ->extract();

        $this->assertEquals(15, $result);
    }

    public function testMaybeMonadNestedMaybeNull(): void
    {
        $result = (new MaybeMonad(new MaybeMonad(null)))
            ->bind(fn($x) => $x + 10)
            ->extract();

        $this->assertNull($result);
    }

    public function testMaybeMonadNestedList(): void
    {
        $result = (new MaybeMonad(new ListMonad([1, 2, 3])))
            ->bind(fn($x) => $x * 2)
            ->extract();

        $this->assertEquals([2, 4, 6], $result);
    }

    public function testMaybeMonadNestedListExtract(): void
    {
        $list = new ListMonad([1, 2, 3]);
        $maybe = new MaybeMonad($list);

        $this->assertEquals([1, 2, 3], $maybe->extract());
    }

    public function testMaybeMonadBindWithMultipleArgs(): void
    {
        $result = (new MaybeMonad(10))
            ->bind(fn($x, $y) => $x + $y, 5)
            ->extract();

        $this->assertEquals(15, $result);
    }

    public function testMaybeMonadZeroValue(): void
    {
        $result = (new MaybeMonad(0))
            ->bind(fn($x) => $x + 5)
            ->extract();

        $this->assertEquals(5, $result);
    }

    public function testMaybeMonadEmptyString(): void
    {
        $result = (new MaybeMonad(""))
            ->bind(fn($x) => $x . "world")
            ->extract();

        $this->assertEquals("world", $result);
    }

    public function testMaybeMonadFalseValue(): void
    {
        $result = (new MaybeMonad(false))
            ->bind(fn($x) => !$x)
            ->extract();

        $this->assertTrue($result);
    }

    public function testListMonad(): void
    {
        $result = (new ListMonad([
            1,
            new IdentityMonad(2),
            new MaybeMonad(3),
            new ListMonad([4])
        ]))->bind(fn($x) => $x + 100)->extract();

        $this->assertEquals($result, [101, 102, 103, [104]]);
    }

    public function testListMonadInvalid(): void
    {
        $this->expectException(\TypeError::class);
        new ListMonad("not traversable");
    }

    public function testListMonadUnit(): void
    {
        $monad = ListMonad::unit([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $monad->extract());
    }

    public function testListMonadUnitSingleValue(): void
    {
        $monad = ListMonad::unit(42);
        $this->assertEquals([42], $monad->extract());
    }

    public function testListMonadEmpty(): void
    {
        $result = (new ListMonad([]))
            ->bind(fn($x) => $x + 1)
            ->extract();

        $this->assertEquals([], $result);
    }

    public function testListMonadMultipleBinds(): void
    {
        $result = (new ListMonad([1, 2, 3]))
            ->bind(fn($x) => $x + 1)
            ->bind(fn($x) => $x * 2)
            ->bind(fn($x) => $x - 1)
            ->extract();

        $this->assertEquals([3, 5, 7], $result);
    }

    public function testListMonadBindWithMultipleArgs(): void
    {
        $result = (new ListMonad([1, 2, 3]))
            ->bind(fn($x, $y) => $x + $y, 10)
            ->extract();

        $this->assertEquals([11, 12, 13], $result);
    }

    public function testListMonadNestedLists(): void
    {
        $result = (new ListMonad([
            [1, 2],
            [3, 4],
            [5, 6]
        ]))->bind(fn($x) => array_sum($x))->extract();

        $this->assertEquals([3, 7, 11], $result);
    }

    public function testListMonadDeeplyNested(): void
    {
        $result = (new ListMonad([
            new ListMonad([1, 2]),
            new ListMonad([3, 4])
        ]))->bind(fn($x) => $x + 10)->extract();

        $this->assertEquals([[11, 12], [13, 14]], $result);
    }

    public function testListMonadWithMixedMonads(): void
    {
        $result = (new ListMonad([
            new IdentityMonad(1),
            new IdentityMonad(2),
            new MaybeMonad(3),
            5
        ]))->bind(fn($x) => $x * 2)->extract();

        $this->assertEquals([2, 4, 6, 10], $result);
    }

    public function testListMonadExtractPreservesStructure(): void
    {
        $list = new ListMonad([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $list->extract());
    }

    public function testListMonadWithTraversable(): void
    {
        $iterator = new \ArrayIterator([1, 2, 3]);
        $result = (new ListMonad($iterator))
            ->bind(fn($x) => $x * 2)
            ->extract();

        $this->assertEquals([2, 4, 6], $result);
    }

    public function testListMonadChainedOperations(): void
    {
        $result = (new ListMonad([1, 2, 3, 4, 5]))
            ->bind(fn($x) => $x * 2)
            ->bind(fn($x) => $x + 1)
            ->bind(fn($x) => $x > 5 ? $x : null)
            ->extract();

        $this->assertEquals([null, null, 7, 9, 11], $result);
    }

    public function testListMonadWithAssociativeArray(): void
    {
        $result = (new ListMonad(["a" => 1, "b" => 2, "c" => 3]))
            ->bind(fn($x) => $x * 10)
            ->extract();

        $this->assertEquals([10, 20, 30], $result);
    }

    public function testIdentityInMaybeInList(): void
    {
        $result = (new ListMonad([
            new MaybeMonad(new IdentityMonad(1)),
            new MaybeMonad(new IdentityMonad(2)),
        ]))->bind(fn($x) => $x + 100)->extract();

        $this->assertEquals([101, 102], $result);
    }

    public function testListInMaybeInIdentity(): void
    {
        $result = (new IdentityMonad(new MaybeMonad(new ListMonad([1, 2, 3]))))
            ->bind(fn($x) => $x * 2)
            ->extract();

        $this->assertEquals([2, 4, 6], $result);
    }

    public function testListInMaybeNullInIdentity(): void
    {
        $result = (new IdentityMonad(new MaybeMonad(null)))
            ->bind(fn($x) => $x + 1)
            ->extract();

        $this->assertNull($result);
    }
}
