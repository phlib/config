<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])

    ->withSets([
        SetList::COMMON,
        SetList::PSR_12,
        SetList::STRICT,
    ])

    ->withSkip([
        // Remove rule, from common/array, due to concise display in test data
        \Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer::class,
        \Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer::class,
        \Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer::class,


        // Remove sniff, from common/control-structures
        \PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer::class,

        // Remove sniff, from common/spaces
        \PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer::class,
        \PhpCsFixer\Fixer\CastNotation\CastSpacesFixer::class,
    ])

    // PER Coding Style 7.1: "The `fn` keyword MUST NOT be succeeded by a space."
    ->withConfiguredRule(
        \PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer::class,
        [
            'closure_fn_spacing' => 'none',
        ]
    );