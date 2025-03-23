<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tool;

use Dazz\PhpMcpTools\Exception\InvalidArgumentException;
use PhpLlm\LlmChain\Chain\Toolbox\Attribute\AsTool;
use Random\RandomException;

#[AsTool(name: 'random_number', description: 'Get a random number between min and max', method: 'number')]
final readonly class Random
{
    public function number(int $min = 0, int $max = PHP_INT_MAX): int
    {
        if ($min < 0) {
            throw new InvalidArgumentException('"min" should not be lower than 0');
        }
        if ($min > $max) {
            throw new InvalidArgumentException('"min" must be less than or equal to "max"');
        }

        try {
            return random_int($min, $max);
        } catch (RandomException $error) {
            throw new InvalidArgumentException('Something went wrong', 0, $error);
        }
    }
}
