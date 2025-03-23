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
        $this->composer = new Composer();
    }
    
    #[Test]
    public function exists_returns_boolean(): void
    {
        $result = $this->composer->exists();
        
        self::assertIsBool($result);
    }
    
    #[Test]
    public function execute_returns_expected_structure(): void
    {
        // Skip if composer is not installed
        if (!$this->composer->exists()) {
            self::markTestSkipped('Composer is not installed');
        }
        
        $result = $this->composer->execute('--version');
        
        self::assertIsString($result);
        self::assertStringContainsString('## Composer --version', $result);
        self::assertStringContainsString('exit_code:', $result);
        self::assertStringContainsString('output:', $result);
    }
    
    #[Test]
    public function execute_with_valid_command_succeeds(): void
    {
        // Skip if composer is not installed
        if (!$this->composer->exists()) {
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
        // Skip if composer is not installed
        if (!$this->composer->exists()) {
            self::markTestSkipped('Composer is not installed');
        }
        
        $result = $this->composer->execute('--version');
        
        self::assertStringContainsString('exit_code: 0', $result);
        self::assertStringContainsString('Composer', $result);
        self::assertStringNotContainsString('Reading config file', $result);
    }
}