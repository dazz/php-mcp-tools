<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Tool;

use Dazz\PhpMcpTools\Exception\InvalidArgumentException;
use PhpLlm\LlmChain\Chain\JsonSchema\Attribute\With;
use PhpLlm\LlmChain\Chain\ToolBox\Attribute\AsTool;
use Random\RandomException;

#[AsTool(name: 'random_number', description: 'Get a random number between min and max', method: 'number')]
final readonly class Random
{
    /**
     * @param string $min minimum number (default: 0)
     * @param string $max maximum number (default: 9223372036854775807)
     *
     * @return string
     */
    public function number(
        #[With(pattern: '/^[0-9]+$/')]
        string $min = '0',
        #[With(pattern: '/^[0-9]+$/')]
        string $max = '9223372036854775807',
    ): string {

        $min = (int) $min;
        $max = (int) $max;

        if ($min < 0) {
            throw new InvalidArgumentException('"min" should not be lower than 0');
        }
        if ($min > $max) {
            throw new InvalidArgumentException('"min" must be less than or equal to "max"');
        }

        try {
            return (string) random_int($min, $max);
        } catch (RandomException $error) {
            throw new InvalidArgumentException('Something went wrong', 0, $error);
        }
    }
}
