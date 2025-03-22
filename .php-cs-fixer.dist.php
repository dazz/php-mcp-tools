<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = (new Finder())
    ->in(__DIR__);

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
        'heredoc_indentation' => ['indentation' => 'start_plus_one'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])
    ->registerCustomFixers([
        new class() extends \PhpCsFixer\AbstractFixer {
            public function getDefinition(): \PhpCsFixer\FixerDefinition\FixerDefinitionInterface
            {
                return new \PhpCsFixer\FixerDefinition\FixerDefinition(
                    'Test method names must be in snake_case and start with test_.',
                    []
                );
            }

            public function isCandidate(\PhpCsFixer\Tokenizer\Tokens $tokens): bool
            {
                return $tokens->isAllTokenKindsFound([\T_CLASS, \T_FUNCTION]);
            }

            protected function applyFix(\SplFileInfo $file, \PhpCsFixer\Tokenizer\Tokens $tokens): void
            {
                if (!str_contains($file->getPathname(), 'Test.php')) {
                    return;
                }

                for ($index = 0, $count = $tokens->count(); $index < $count; ++$index) {
                    if (!$tokens[$index]->isGivenKind(\T_FUNCTION)) {
                        continue;
                    }

                    $nameIndex = $tokens->getNextMeaningfulToken($index);
                    if (!$tokens[$nameIndex]->isGivenKind(\T_STRING)) {
                        continue;
                    }

                    $methodName = $tokens[$nameIndex]->getContent();
                    if (str_starts_with($methodName, 'test') && !str_starts_with($methodName, 'test_')) {
                        $newName = 'test_' . preg_replace('/^test/', '', lcfirst(preg_replace('/(?<=\\w)(?=[A-Z])/', '_$1', $methodName)));
                        $newName = strtolower($newName);
                        $tokens[$nameIndex] = new \PhpCsFixer\Tokenizer\Token([\T_STRING, $newName]);
                    }
                }
            }
        },
    ])
    ->setFinder($finder);