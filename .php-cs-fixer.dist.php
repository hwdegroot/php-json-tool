<?php
use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = Finder::create()
    ->exclude('bootstrap')
    ->exclude('public')
    ->exclude('storage')
    ->exclude('tests/__snapshots__')
    ->in(__DIR__);

$config = new Config();
$config->setRiskyAllowed(true);
return $config->setRules([
        '@PHP56Migration:risky' => true,
        '@PHP56Migration:risky' => true,
        '@PHP70Migration' => true,
        '@PHP70Migration:risky' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        '@PHP73Migration' => true,
        '@PHP74Migration' => true,
        '@PHP74Migration:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PSR2' => true,
        'align_multiline_comment' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_before_statement' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'comment_to_phpdoc' => true,
        'compact_nullable_typehint' => true,
        'escape_implicit_backslashes' => true,
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'final_internal_class' => true,
        'fully_qualified_strict_types' => true,
        'function_to_constant' => ['functions' => ['get_class', 'get_called_class', 'php_sapi_name', 'phpversion', 'pi']],
        'header_comment' => false ,
        'heredoc_to_nowdoc' => true,
        'list_syntax' => ['syntax' => 'long'],
        'logical_operators' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'method_chaining_indentation' => true,
        'multiline_comment_opening_closing' => true,
        'no_alternative_syntax' => true,
        'no_binary_string' => true,
        'no_extra_blank_lines' => ['tokens' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block']],
        'no_null_property_initialization' => true,
        'no_superfluous_elseif' => true,
        'no_unneeded_curly_braces' => true,
        'no_unneeded_final_method' => true,
        'no_unreachable_default_argument_value' => true,
        'no_unset_on_property' => false,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'php_unit_internal_class' => true,
        'php_unit_method_casing' => true,
        'php_unit_set_up_tear_down_visibility' => true,
        'php_unit_strict' => false,
        'php_unit_test_annotation' => true,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
        'php_unit_test_class_requires_covers' => false,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types_order' => true,
        'return_assignment' => true,
        'semicolon_after_instruction' => true,
        'single_line_comment_style' => true,
        'strict_comparison' => false,
        'strict_param' => false,
        'string_line_ending' => true,
        'yoda_style' => false,
    ])
    ->setFinder($finder);

