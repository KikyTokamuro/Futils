<?php

use PHPUnit\Framework\TestCase;
use Kikytokamuro\Futils\Monad\ListMonad;
use Kikytokamuro\Futils\Monad\MaybeMonad;
use Kikytokamuro\Futils\Monad\IdentityMonad;

class ListMonadTest extends TestCase
{
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
