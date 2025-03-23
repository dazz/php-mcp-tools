<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = (new Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => false, // overrides @Symfony rule set
        'global_namespace_import' => false, // overrides @Symfony rule set
        'concat_space' => ['spacing' => 'one'], // overrides @Symfony rule set
        'phpdoc_align' => false,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'nullable_type_declaration' => true,
        'nullable_type_declaration_for_default_null_value' => true, // overrides @Symfony rule set
        'heredoc_indentation' => ['indentation' => 'start_plus_one'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])
    ->setIndent("    ") // Ensure proper indentation (4 spaces)
    ->setLineEnding("\n")

    ->setFinder($finder);