<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tool;

use PhpLlm\LlmChain\Chain\Toolbox\Attribute\AsTool;

#[AsTool(name: 'random_number', description: 'Get a random number between min and max', method: 'number')]
final readonly class Random
{
    public function number(int $min = 0, int $max = PHP_INT_MAX): string
    {
        return (string) random_int($min, $max);
    }
}