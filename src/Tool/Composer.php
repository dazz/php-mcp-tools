<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tool;

use Dazz\PhpMcpTools\Exception\InvalidArgumentException;
use PhpLlm\LlmChain\Chain\Toolbox\Attribute\AsTool;

#[AsTool(name: 'composer_exists', description: 'Check if the Composer executable is available on the system', method: 'exists')]
#[AsTool(name: 'composer_execute', description: 'Execute a Composer command', method: 'execute')]
final readonly class Composer
{
    public function exists(): bool
    {
        // Check if running on Windows or Unix-like system
        $command = PHP_OS_FAMILY === 'Windows' ? 'where composer' : 'which composer';

        // Execute the command and suppress output
        exec($command, $output, $returnCode);

        // Return true if the command was successful (composer exists)
        return $returnCode === 0;
    }
    
    /**
     * @param string $command The Composer command to execute (e.g., "require", "update", "install")
     * @param array<string> $args Additional arguments for the command
     * @param string|null $workingDir The working directory to run composer in
     * @param bool $captureStderr Whether to capture stderr output
     * @return string Execution result
     * @throws InvalidArgumentException If composer executable is not found
     */
    public function execute(
        string $command,
        array $args = [],
        ?string $workingDir = null,
        bool $captureStderr = true
    ): string {
        if (!$this->exists()) {
            throw new InvalidArgumentException('Composer executable not found. Please install Composer.');
        }
        
        if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $command)) {
            throw new InvalidArgumentException('Invalid Composer command. Only alphanumeric characters, underscores, and hyphens are allowed.');
        }
        
        $escapedArgs = array_map('escapeshellarg', $args);
        
        // Add quiet mode to suppress unnecessary output
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
        
        $currentDir = null;
        if ($workingDir !== null) {
            $currentDir = getcwd();
            chdir($workingDir);
        }
        
        exec($fullCommand, $output, $exitCode);
        
        if ($currentDir !== null) {
            chdir($currentDir);
        }

        $output = implode("\n", $output);
        
        return <<<TOOL
            ## Composer {$command}
            exit_code: {$exitCode}
            output: {$output}
            TOOL;
    }
}
