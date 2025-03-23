<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tests\Tool;

use Dazz\PhpMcpTools\Exception\InvalidArgumentException;
use Dazz\PhpMcpTools\Tool\Composer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Composer::class)]
final class ComposerTest extends TestCase
{
    private Composer $composer;

    protected function setUp(): void
    {
        $this->composer = new Composer(__DIR__);
    }

    #[Test]
    public function exists_returns_string(): void
    {
        if (!str_contains($this->composer->exists(), 'exists')) {
            self::markTestSkipped('Composer is not installed');
        }

        $result = $this->composer->exists();

        self::assertStringContainsString('composer executable', $result);
    }

    #[Test]
    public function execute_returns_expected_structure(): void
    {
        if (!str_contains($this->composer->exists(), 'exists')) {
            self::markTestSkipped('Composer is not installed');
        }

        $result = $this->composer->execute('--version');

        self::assertIsString($result);
        self::assertStringContainsString('command: composer --version --no-interaction', $result);
        self::assertStringContainsString('exit_code:', $result);
        self::assertStringContainsString('output:', $result);
    }

    #[Test]
    public function execute_with_valid_command_succeeds(): void
    {
        if (!str_contains($this->composer->exists(), 'exists')) {
            self::markTestSkipped('Composer is not installed');
        }

        $result = $this->composer->execute('--version');

        self::assertStringContainsString('exit_code: 0', $result);
        self::assertStringContainsString('Composer', $result);
    }

    #[Test]
    public function execute_with_invalid_command_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Composer command');

        $this->composer->execute('invalid;command');
    }

    #[Test]
    public function execute_with_args_works_correctly(): void
    {
        if (!str_contains($this->composer->exists(), 'exists')) {
            self::markTestSkipped('Composer is not installed');
        }

        $result = $this->composer->execute('--version');

        self::assertStringContainsString('exit_code: 0', $result);
        self::assertStringContainsString('Composer', $result);
        self::assertStringNotContainsString('Reading config file', $result);
    }

    #[Test]
    public function constructor_with_working_dir_sets_directory(): void
    {
        $tempDir = sys_get_temp_dir();
        $composer = new Composer($tempDir);

        if (!str_contains($composer->exists(), 'exists')) {
            self::markTestSkipped('Composer is not installed');
        }

        // This test is mainly to verify that no errors occur when using a custom working directory
        $result = $composer->execute('--version');

        self::assertStringContainsString('exit_code: 0', $result);
    }
}
