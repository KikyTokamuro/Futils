<?php

use PHPUnit\Framework\TestCase;
use Kikytokamuro\Futils\Monad\EitherMonad;

class EitherMonadTest extends TestCase
{
    public function testRight(): void
    {
        $either = EitherMonad::right(42);
        $this->assertTrue($either->isRight());
        $this->assertFalse($either->isLeft());
        $this->assertEquals(42, $either->extract());
    }

    public function testLeft(): void
    {
        $either = EitherMonad::left("error");
        $this->assertTrue($either->isLeft());
        $this->assertFalse($either->isRight());
        $this->assertEquals("error", $either->extract());
    }

    public function testUnit(): void
    {
        $either = EitherMonad::unit(42);
        $this->assertTrue($either->isRight());
        $this->assertEquals(42, $either->extract());
    }

    public function testBindRight(): void
    {
        $result = EitherMonad::right(10)
            ->bind(fn($x) => $x * 2);

        $this->assertTrue($result->isRight());
        $this->assertEquals(20, $result->extract());
    }

    public function testBindLeft(): void
    {
        $result = EitherMonad::left("error")
            ->bind(fn($x) => $x * 2);

        $this->assertTrue($result->isLeft());
        $this->assertEquals("error", $result->extract());
    }

    public function testBindRightToLeft(): void
    {
        $result = EitherMonad::right(10)
            ->bind(fn($x) => EitherMonad::left("error"));

        $this->assertTrue($result->isLeft());
        $this->assertEquals("error", $result->extract());
    }

    public function testBindLeftStaysLeft(): void
    {
        $result = EitherMonad::left("error")
            ->bind(fn($x) => EitherMonad::right(42));

        $this->assertTrue($result->isLeft());
        $this->assertEquals("error", $result->extract());
    }

    public function testBindWithMultipleArgs(): void
    {
        $result = EitherMonad::right(10)
            ->bind(fn($x, $y) => $x + $y, 5);

        $this->assertEquals(15, $result->extract());
    }

    public function testBindChaining(): void
    {
        $result = EitherMonad::right(5)
            ->bind(fn($x) => $x + 3)
            ->bind(fn($x) => $x * 2)
            ->bind(fn($x) => $x - 1);

        $this->assertEquals(15, $result->extract());
    }

    public function testBindChainingWithLeft(): void
    {
        $result = EitherMonad::right(5)
            ->bind(fn($x) => $x + 3)
            ->bind(fn($x) => EitherMonad::left("error"))
            ->bind(fn($x) => $x * 2);

        $this->assertTrue($result->isLeft());
        $this->assertEquals("error", $result->extract());
    }

    public function testMapRight(): void
    {
        $result = EitherMonad::right(10)
            ->map(fn($x) => $x * 2);

        $this->assertEquals(20, $result->extract());
    }

    public function testMapLeft(): void
    {
        $result = EitherMonad::left("error")
            ->map(fn($x) => $x * 2);

        $this->assertTrue($result->isLeft());
        $this->assertEquals("error", $result->extract());
    }

    public function testLeftMapLeft(): void
    {
        $result = EitherMonad::left("error")
            ->leftMap(fn($x) => strtoupper($x));

        $this->assertEquals("ERROR", $result->extract());
    }

    public function testLeftMapRight(): void
    {
        $result = EitherMonad::right(42)
            ->leftMap(fn($x) => strtoupper($x));

        $this->assertEquals(42, $result->extract());
    }

    public function testFoldRight(): void
    {
        $result = EitherMonad::right(10)
            ->fold(
                fn($err) => "Error: $err",
                fn($val) => "Success: $val"
            );

        $this->assertEquals("Success: 10", $result);
    }

    public function testFoldLeft(): void
    {
        $result = EitherMonad::left("error")
            ->fold(
                fn($err) => "Error: $err",
                fn($val) => "Success: $val"
            );

        $this->assertEquals("Error: error", $result);
    }

    public function testGetOrElseRight(): void
    {
        $result = EitherMonad::right(42)
            ->getOrElse(0);

        $this->assertEquals(42, $result);
    }

    public function testGetOrElseLeft(): void
    {
        $result = EitherMonad::left("error")
            ->getOrElse(0);

        $this->assertEquals(0, $result);
    }

    public function testDivisionSuccess(): void
    {
        $divide = fn($a, $b) => $b === 0
            ? EitherMonad::left("Division by zero")
            : EitherMonad::right($a / $b);

        $result = $divide(10, 2);
        $this->assertTrue($result->isRight());
        $this->assertEquals(5, $result->extract());
    }

    public function testDivisionFailure(): void
    {
        $divide = fn($a, $b) => $b === 0
            ? EitherMonad::left("Division by zero")
            : EitherMonad::right($a / $b);

        $result = $divide(10, 0);
        $this->assertTrue($result->isLeft());
        $this->assertEquals("Division by zero", $result->extract());
    }

    public function testChainedOperations(): void
    {
        $result = EitherMonad::right(10)
            ->bind(fn($x) => $x + 5)
            ->bind(fn($x) => $x > 10 ? EitherMonad::right($x) : EitherMonad::left("Too small"));

        $this->assertTrue($result->isRight());
        $this->assertEquals(15, $result->extract());
    }

    public function testChainedOperationsFailure(): void
    {
        $result = EitherMonad::right(10)
            ->bind(fn($x) => $x + 5)
            ->bind(fn($x) => $x > 20 ? EitherMonad::right($x) : EitherMonad::left("Too small"));

        $this->assertTrue($result->isLeft());
        $this->assertEquals("Too small", $result->extract());
    }
}
