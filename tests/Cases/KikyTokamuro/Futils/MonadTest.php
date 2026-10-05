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

    public function testMaybeMonad(): void
    {
        $bindtest = (new MaybeMonad("test"))
            ->bind(fn() => new MaybeMonad(null))
            ->extract();
        $this->assertEquals($bindtest, null);

        $bindtest2 = (new MaybeMonad(null))
            ->bind(fn($x) => $x + 1);
        $this->assertEquals($bindtest2, new MaybeMonad(null));

        $unittest = new MaybeMonad(null);
        $this->assertEquals($unittest, $unittest->unit(null));
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
}
