<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tool;

use Dazz\PhpMcpTools\Exception\InvalidArgumentException;
use PhpLlm\LlmChain\Chain\JsonSchema\Attribute\With;
use PhpLlm\LlmChain\Chain\Toolbox\Attribute\AsTool;
use Webmozart\Assert\Assert;

#[AsTool(name: 'composer_exists', description: 'Check if the Composer executable is available on the system', method: 'exists')]
#[AsTool(name: 'composer_execute', description: 'Execute a Composer command', method: 'execute')]
final readonly class Composer
{
    public function __construct(
        private string $projectDir,
    ) {
    }

    /**
     * @return string Result of checking if executable exists
     */
    public function exists(): string
    {
        return $this->isExecutableExisting() ? 'composer executable exists' : 'composer executable not found';
    }

    /**
     * @param string $command The Composer command to execute (e.g., "require", "update", "install") (default: list)
     * @param array<string> $options Additional options for the command
     * @param array<string> $arguments Additional arguments for the command
     * @param bool $captureStderr Whether to capture stderr output (default: false)
     *
     * @return string Execution result
     *
     * @throws \InvalidArgumentException If composer executable is not found
     */
    public function execute(
        #[With(pattern: '/^[a-z\-]+$/')]
        string $command = 'list',
        array $options = [],
        array $arguments = [],
        bool $captureStderr = false,
    ): string {
        if (!$this->isExecutableExisting()) {
            throw new InvalidArgumentException('Composer executable not found. Please install Composer.');
        }

        if (!preg_match('/^[a-z\-]+$/', $command)) {
            throw new InvalidArgumentException('Invalid Composer command. Only alphanumeric characters, underscores, and hyphens are allowed.');
        }

        $options[] = '--no-interaction';

        $escapedOptions = array_map('escapeshellcmd', $options);
        $escapedArguments = array_map('escapeshellarg', $arguments);

        $fullCommand = sprintf(
            'composer %s %s %s%s',
            escapeshellcmd($command),
            implode(' ', $escapedOptions),
            implode(' ', $escapedArguments),
            $captureStderr ? ' 2>&1' : ''
        );

        $currentDir = getcwd();
        Assert::string($currentDir);

        chdir($this->projectDir);

        exec($fullCommand, $output, $exitCode);

        chdir($currentDir);

        $output = implode("\n", $output);

        return <<<TOOL
            command: {$fullCommand}
            exit_code: {$exitCode}
            output: 

            {$output}
            TOOL;
    }

    private function isExecutableExisting(): bool
    {
        $command = PHP_OS_FAMILY === 'Windows' ? 'where composer' : 'which composer';
        exec($command, $output, $returnCode);

        return $returnCode === 0;
    }
}
