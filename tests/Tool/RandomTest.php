<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tests\Tool;

use Dazz\PhpMcpTools\Tool\Random;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Random::class)]
final class RandomTest extends TestCase
{
    #[Test]
    public function random_number_without_args(): void
    {
        $random = new Random();

        self::assertIsInt($random->number());
    }

    #[Test]
    public function random_number_with_args(): void
    {
        $random = new Random();

        self::assertIsInt($random->number(42, 42));
        self::assertEquals(42, $random->number(42, 42));
    }
}