<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tool;

use PhpLlm\LlmChain\Chain\Toolbox\Attribute\AsTool;

#[AsTool(name: 'console_exists', description: 'Check if the executable is available in the project', method: 'exists')]
final readonly class Console
{
    public function __construct(private string $consoleExecutable)
    {
    }

    public function exists(): string
    {
        return $this->isExecutableExisting() ? 'executable exists' : 'executable not found';
    }

    private function isExecutableExisting(): bool
    {
        return file_exists($this->consoleExecutable);
    }
}
