<?php

use PHPUnit\Framework\TestCase;
use Kikytokamuro\Futils\Monad\TryMonad;
use Kikytokamuro\Futils\Monad\EitherMonad;

class TryMonadTest extends TestCase
{
    public function testSuccess(): void
    {
        $try = TryMonad::success(42);
        $this->assertTrue($try->isSuccess());
        $this->assertFalse($try->isFailure());
        $this->assertEquals(42, $try->extract());
    }

    public function testFailure(): void
    {
        $exception = new \RuntimeException("error");
        $try = TryMonad::failure($exception);
        $this->assertTrue($try->isFailure());
        $this->assertFalse($try->isSuccess());
        $this->assertSame($exception, $try->getException());
    }

    public function testUnit(): void
    {
        $try = TryMonad::unit(42);
        $this->assertTrue($try->isSuccess());
        $this->assertEquals(42, $try->extract());
    }

    public function testOfSuccess(): void
    {
        $try = TryMonad::of(fn($x) => $x * 2, 5);
        $this->assertTrue($try->isSuccess());
        $this->assertEquals(10, $try->extract());
    }

    public function testOfFailure(): void
    {
        $try = TryMonad::of(function () {
            throw new \RuntimeException("error");
        });
        $this->assertTrue($try->isFailure());
        $this->assertInstanceOf(\RuntimeException::class, $try->getException());
    }

    public function testBindSuccess(): void
    {
        $result = TryMonad::success(10)
            ->bind(fn($x) => $x * 2);

        $this->assertTrue($result->isSuccess());
        $this->assertEquals(20, $result->extract());
    }

    public function testBindFailure(): void
    {
        $result = TryMonad::failure(new \RuntimeException("error"))
            ->bind(fn($x) => $x * 2);

        $this->assertTrue($result->isFailure());
    }

    public function testBindThrowsException(): void
    {
        $result = TryMonad::success(10)
            ->bind(function ($x) {
                throw new \RuntimeException("error in bind");
            });

        $this->assertTrue($result->isFailure());
        $this->assertEquals("error in bind", $result->getException()->getMessage());
    }

    public function testBindWithMultipleArgs(): void
    {
        $result = TryMonad::success(10)
            ->bind(fn($x, $y) => $x + $y, 5);

        $this->assertEquals(15, $result->extract());
    }

    public function testBindChaining(): void
    {
        $result = TryMonad::success(5)
            ->bind(fn($x) => $x + 3)
            ->bind(fn($x) => $x * 2)
            ->bind(fn($x) => $x - 1);

        $this->assertEquals(15, $result->extract());
    }

    public function testBindChainingWithFailure(): void
    {
        $result = TryMonad::success(5)
            ->bind(fn($x) => $x + 3)
            ->bind(function ($x) {
                throw new \RuntimeException("error");
            })
            ->bind(fn($x) => $x * 2);

        $this->assertTrue($result->isFailure());
    }

    public function testMapSuccess(): void
    {
        $result = TryMonad::success(10)
            ->map(fn($x) => $x * 2);

        $this->assertEquals(20, $result->extract());
    }

    public function testMapFailure(): void
    {
        $result = TryMonad::failure(new \RuntimeException("error"))
            ->map(fn($x) => $x * 2);

        $this->assertTrue($result->isFailure());
    }

    public function testMapThrowsException(): void
    {
        $result = TryMonad::success(10)
            ->map(function ($x) {
                throw new \RuntimeException("error in map");
            });

        $this->assertTrue($result->isFailure());
    }

    public function testRecoverSuccess(): void
    {
        $result = TryMonad::success(42)
            ->recover(fn($e) => 0);

        $this->assertTrue($result->isSuccess());
        $this->assertEquals(42, $result->extract());
    }

    public function testRecoverFailure(): void
    {
        $result = TryMonad::failure(new \RuntimeException("error"))
            ->recover(fn($e) => "recovered");

        $this->assertTrue($result->isSuccess());
        $this->assertEquals("recovered", $result->extract());
    }

    public function testRecoverThrowsException(): void
    {
        $result = TryMonad::failure(new \RuntimeException("error"))
            ->recover(function ($e) {
                throw new \RuntimeException("error in recover");
            });

        $this->assertTrue($result->isFailure());
    }

    public function testFoldSuccess(): void
    {
        $result = TryMonad::success(10)
            ->fold(
                fn($e) => "Error: " . $e->getMessage(),
                fn($val) => "Success: $val"
            );

        $this->assertEquals("Success: 10", $result);
    }

    public function testFoldFailure(): void
    {
        $result = TryMonad::failure(new \RuntimeException("error"))
            ->fold(
                fn($e) => "Error: " . $e->getMessage(),
                fn($val) => "Success: $val"
            );

        $this->assertEquals("Error: error", $result);
    }

    public function testGetOrElseSuccess(): void
    {
        $result = TryMonad::success(42)
            ->getOrElse(0);

        $this->assertEquals(42, $result);
    }

    public function testGetOrElseFailure(): void
    {
        $result = TryMonad::failure(new \RuntimeException("error"))
            ->getOrElse(0);

        $this->assertEquals(0, $result);
    }

    public function testToEitherSuccess(): void
    {
        $result = TryMonad::success(42)
            ->toEither();

        $this->assertInstanceOf(EitherMonad::class, $result);
        $this->assertTrue($result->isRight());
        $this->assertEquals(42, $result->extract());
    }

    public function testToEitherFailure(): void
    {
        $result = TryMonad::failure(new \RuntimeException("error"))
            ->toEither();

        $this->assertInstanceOf(EitherMonad::class, $result);
        $this->assertTrue($result->isLeft());
    }

    public function testJsonDecodeSuccess(): void
    {
        $result = TryMonad::of(fn($json) => json_decode($json, true), '{"a":1}');
        $this->assertTrue($result->isSuccess());
        $this->assertEquals(['a' => 1], $result->extract());
    }

    public function testJsonDecodeFailure(): void
    {
        $result = TryMonad::of(fn($json) => json_decode($json, true, 512, JSON_THROW_ON_ERROR), 'invalid json');
        $this->assertTrue($result->isFailure());
    }

    public function testDivisionSuccess(): void
    {
        $divide = fn($a, $b) => TryMonad::of(
            fn() => $b === 0 ? throw new \DivisionByZeroError("Division by zero") : $a / $b
        );

        $result = $divide(10, 2);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals(5, $result->extract());
    }

    public function testDivisionFailure(): void
    {
        $divide = fn($a, $b) => TryMonad::of(
            fn() => $b === 0 ? throw new \DivisionByZeroError("Division by zero") : $a / $b
        );

        $result = $divide(10, 0);
        $this->assertTrue($result->isFailure());
    }

    public function testChainedFileOperations(): void
    {
        $result = TryMonad::of(fn() => file_get_contents(__FILE__))
            ->bind(fn($content) => strlen($content))
            ->map(fn($len) => $len > 0 ? $len : throw new \RuntimeException("Empty file"));

        $this->assertTrue($result->isSuccess());
        $this->assertGreaterThan(0, $result->extract());
    }
}
