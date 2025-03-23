<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tool;

use Dazz\PhpMcpTools\Exception\InvalidArgumentException;
use PhpLlm\LlmChain\Chain\Toolbox\Attribute\AsTool;

#[AsTool(name: 'composer_exists', description: 'Check if the Composer executable is available on the system', method: 'exists')]
#[AsTool(name: 'composer_execute', description: 'Execute a Composer command', method: 'execute')]
final readonly class Composer
{
    public function __construct(
        private string $workingDir,
    ) {
    }
    
    public function exists(): string
    {
        return $this->isExecutableExisting() ? 'composer executable exists' : 'composer executable not found';
    }
    
    /**
     * @param string $command The Composer command to execute (e.g., "require", "update", "install")
     * @param array<string> $args Additional arguments for the command
     * @param bool $captureStderr Whether to capture stderr output
     * @return string Execution result
     * @throws InvalidArgumentException If composer executable is not found
     */
    public function execute(
        string $command,
        array $args = [],
        bool $captureStderr = true
    ): string {
        if (!$this->isExecutableExisting()) {
            throw new InvalidArgumentException('Composer executable not found. Please install Composer.');
        }
        
        if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $command)) {
            throw new InvalidArgumentException('Invalid Composer command. Only alphanumeric characters, underscores, and hyphens are allowed.');
        }
        
        $escapedArgs = array_map('escapeshellarg', $args);
        
        $quietArg = '--no-interaction --quiet';
        if (!in_array('--verbose', $args) && !in_array('-v', $args)) {
            $quietArg .= ' --no-progress';
        }
        
        $fullCommand = sprintf(
            'composer %s %s %s%s', 
            escapeshellarg($command),
            $quietArg, 
            implode(' ', $escapedArgs),
            $captureStderr ? ' 2>&1' : ''
        );
        
        $currentDir = getcwd();
        chdir($this->workingDir);
        
        exec($fullCommand, $output, $exitCode);
        
        chdir($currentDir);

        $output = implode("\n", $output);
        
        return <<<TOOL
            ## Composer {$command}
            exit_code: {$exitCode}
            output: {$output}
            TOOL;
    }

    private function isExecutableExisting(): bool
    {
        $command = PHP_OS_FAMILY === 'Windows' ? 'where composer' : 'which composer';
        exec($command, $output, $returnCode);
        return $returnCode === 0;
    }
}
