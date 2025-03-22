<?php

declare(strict_types=1);

namespace Dazz\PhpMcpTools\Exception;

use Dazz\PhpMcpTools\Exception\ExceptionInterface;

final readonly class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{

}