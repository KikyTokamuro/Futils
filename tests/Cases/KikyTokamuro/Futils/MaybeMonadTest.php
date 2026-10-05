<?php

use PHPUnit\Framework\TestCase;
use Kikytokamuro\Futils\Monad\ListMonad;
use Kikytokamuro\Futils\Monad\MaybeMonad;
use Kikytokamuro\Futils\Monad\IdentityMonad;

class MaybeMonadTest extends TestCase
{
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
}
