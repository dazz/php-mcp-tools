# PHP MCP Tools

PHP MCP Tools is a collection of utilities for managing and automating PHP projects with Composer. 
It provides tools to analyze dependencies, check for outdated packages, and streamline project maintenance.

> The Model Context Protocol (MCP) is an open protocol that enables seamless integration between LLM applications and external data sources and tools. Whether you're building an AI-powered IDE, enhancing a chat interface, or creating custom AI workflows, MCP provides a standardized way to connect LLMs with the context they need.

## Installation

```bash
composer require dazz/php-mcp-tools
```

## Requirements

- PHP 8.2 or higher
- Composer (optional)
- Symfony CLI (optional)
- Pie (optional)

## Usage

### When used with php-llm/llm-chain-bundle

```yaml
llm_chain:

services:
  _defaults:
    autowire: true
    autoconfigure: true

  # ...
  Dazz\PhpMcpTools\Tool\Random: ~
  Dazz\PhpMcpTools\Tool\Composer:
    arguments:
      $projectDir: '%kernel.project_dir%'
```

## Tools

### Random

1. `random_number`
   - Get a random number between min and max
   - Inputs:
     - `min` (int): min number
     - `max` (int): max number
   - Returns: A random number between min and max

### Composer

1. `composer_exists`
    - Check if composer executable is existing
    - Returns: composer executable exists

2. `composer_execute`
    - Execute a composer command
    - Inputs:
      - `command` (string): the command to execute
      - `options` (array): additional options for the command
      - `arguments` (array): additional arguments for the command
      - `captureStdErr` (bool): whether to capture stderr output