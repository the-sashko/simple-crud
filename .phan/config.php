<?php
return [
    'target_php_version'                                => 7.3,

    'allow_missing_properties'                          => true,

    'null_casts_as_any_type'                            => true,

    'null_casts_as_array'                               => true,

    'array_casts_as_null'                               => true,

    'scalar_implicit_cast'                              => true,

    'scalar_array_key_cast'                             => true,

    'scalar_implicit_partial'                           => [

    ],

    'strict_method_checking'                            => true,

    'strict_object_checking'                            => true,

    'strict_param_checking'                             => true,

    'strict_property_checking'                          => true,

    'strict_return_checking'                            => true,

    'ignore_undeclared_variables_in_global_scope'       => true,

    'ignore_undeclared_functions_with_known_signatures' => true,

    'backward_compatibility_checks'                     => false,

    'check_docblock_signature_return_type_match'        => true,

    'prefer_narrowed_phpdoc_param_type'                 => true,

    'prefer_narrowed_phpdoc_return_type'                => false,

    'analyze_signature_compatibility'                   => true,

    'phpdoc_type_mapping'                               => [

    ],

    'dead_code_detection'                               => true,

    'unused_variable_detection'                         => true,

    'redundant_condition_detection'                     => true,

    'assume_real_types_for_internal_functions'          => true,

    'quick_mode'                                        => false,

    'generic_types_enabled'                             => true,

    'globals_type_map'                                  => [

    ],

    'directory_list'                                    => [
        './'
    ],

    'exclude_file_regex'                                => null,

    'exclude_file_list'                                 => [

    ],

    'exclude_analysis_directory_list'                   => [
        '.phan/',
        'example/',
        'exceptions/'
    ],

    'enable_class_alias_support'                        => true,

    'enable_include_path_checks'                        => true,

    'processes'                                         => 1,

    'analyzed_file_extensions' => [
        'php',
        'phtml',
    ],

    'autoload_internal_extension_signatures'            => [

    ],

    'plugins'                                           => [

    ],

    'directory_list'                                    => [
        './'
    ],

    'file_list'                                         => [

    ]
];
