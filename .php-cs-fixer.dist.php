<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = (new Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

// Custom rule for test method names in snake_case
$snakeCaseTestMethodsRules = [
    'method_naming' => [
        'test' => '/^test_[a-z][a-z0-9_]*$/',
    ],
];

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => false, // overrides @Symfony rule set
        'global_namespace_import' => false, // overrides @Symfony rule set
        'concat_space' => ['spacing' => 'one'], // overrides @Symfony rule set
        'no_useless_else' => true,
        'no_useless_return' => true,
        'nullable_type_declaration' => true,
        'nullable_type_declaration_for_default_null_value' => true, // overrides @Symfony rule set
        'heredoc_indentation' => ['indentation' => 'start_plus_one'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])

    ->setFinder($finder);