<?php

use PHPUnit\Framework\TestCase;
use Kikytokamuro\Futils\Monad\ListMonad;
use Kikytokamuro\Futils\Monad\MaybeMonad;
use Kikytokamuro\Futils\Monad\IdentityMonad;

class IdentityMonadTest extends TestCase
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
}
