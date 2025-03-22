<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tests\Tool;

use Dazz\PhpMcpTools\Exception\InvalidArgumentException;
use Dazz\PhpMcpTools\Tool\Random;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Random::class)]
final class RandomTest extends TestCase
{
    #[Test]
    public function random_number_in_range(): void
    {
        $random = new Random();

        self::assertGreaterThanOrEqual(1, $random->number());
        self::assertLessThanOrEqual(PHP_INT_MAX, $random->number());
    }

    #[Test]
    public function random_number_with_args(): void
    {
        $random = new Random();

        self::assertEquals(42, $random->number(42, 42));
    }

    #[Test]
    public function random_number_invalid_when_min_less_than_0(): void
    {
        $random = new Random();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"min" should not be lower than 0');

        $random->number(-1);
    }

    #[Test]
    public function random_number_invalid_when_max_less_than_min(): void
    {
        $random = new Random();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('"min" must be less than or equal to "max"');

        $random->number(42, 23);
    }
}