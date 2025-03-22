# PHP MCP Tools

A PHP library that provides MCP tools for working with Model Context Protocol (MCP). 

> The Model Context Protocol (MCP) is an open protocol that enables seamless integration between LLM applications and external data sources and tools. Whether you're building an AI-powered IDE, enhancing a chat interface, or creating custom AI workflows, MCP provides a standardized way to connect LLMs with the context they need.

## Installation

```bash
composer require dazz/php-mcp-tools
```

## Requirements

- PHP 8.2 or higher
- Composer
- Symfony CLI
- Pie

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
```

## Tools

### Random

1. `random_number`
   - Get a random number between min and max
   - Inputs:
     - `min` (int): min number
     - `max` (int): max number
   - Returns: A random number