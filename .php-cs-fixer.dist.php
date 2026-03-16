<?php

declare(strict_types=1);

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->exclude('var')
    ->notPath([
        'config/bundles.php',
        'config/reference.php',
    ]);

$config = new PhpCsFixer\Config();
$config
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        '@PHP71Migration' => true,
        '@PHP73Migration' => true,
        '@PHP74Migration' => true,
        '@PHP80Migration' => true,
        '@PHP81Migration' => true,
        '@PHP82Migration' => true,
        '@PHP83Migration' => true,
        '@PHPUnit48Migration:risky' => true,
        '@PhpCsFixer' => true,
        'align_multiline_comment' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'default',
                'exit',
                'goto',
                'include',
                'include_once',
                'phpdoc',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'yield',
            ],
        ],
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ],
        ],
        'concat_space' => ['spacing' => 'one'],
        'constant_case' => ['case' => 'lower'],
        'declare_strict_types' => true,
        'dir_constant' => true,
        'echo_tag_syntax' => ['format' => 'long'],
        'general_phpdoc_annotation_remove' => [
            'annotations' => ['author', 'package'],
        ],
        'heredoc_to_nowdoc' => true,
        'increment_style' => ['style' => 'post'],
        'fully_qualified_strict_types' => true,
        'line_ending' => true,
        'linebreak_after_opening_tag' => true,
        'mb_str_functions' => true,
        'modernize_types_casting' => true,
        'multiline_whitespace_before_semicolons' => true,
        'native_function_invocation' => [
            'include' => ['@internal'],
        ],
        'no_closing_tag' => true,
        'no_php4_constructor' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_trailing_whitespace' => true,
        'ordered_imports' => [
            'imports_order' => [
                'const',
                'class',
                'function',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => true],
        'phpdoc_order' => true,
        'phpdoc_summary' => false,
        'phpdoc_to_comment' => false,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
        'protected_to_private' => false,
        'semicolon_after_instruction' => true,
        'single_line_comment_style' => [
            'comment_types' => ['hash'],
        ],
        'strict_param' => true,
        'yoda_style' => [
            'always_move_variable' => false,
            'equal' => false,
            'identical' => false,
        ],
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);

$config->setUnsupportedPhpVersionAllowed(true);
$config->setParallelConfig(ParallelConfigFactory::detect());

return $config;
