<?php

return [
    'shield_resource' => [
        'should_register_navigation' => true,
        'slug' => 'shield/roles',
        'navigation_sort' => -1,
        'navigation_badge' => false,
        'navigation_group' => false,
        'is_globally_searchable' => false,
        'show_model_path' => false,
        'is_scoped_to_tenant' => false,
    ],

    'auth_provider_model' => [
        'fqcn' => 'App\\Models\\User',
    ],

    'super_admin' => [
        'enabled' => false,
        'name' => 'super_admin',
        'define_via_gate' => false,
        'intercept_gate' => 'before', // after
    ],

    'panel_user' => [
        'enabled' => false,
        'name' => 'panel_user',
    ],

    'permission_prefixes' => [
        'resource' => [
			'view_any',
            'view',
            'create',
            'update',
            /*'restore',
            'restore_any',
            'replicate',
            'reorder',*/
            'delete',
            //'delete_any',
            //'force_delete',
            //'force_delete_any',
        ],

        'page' => 'page',
        'widget' => 'widget',
    ],

    'entities' => [
        'pages' => true,
        'widgets' => true,
        'resources' => true,
        'custom_permissions' => false,
    ],

    'generator' => [
        'option' => 'policies_and_permissions',
        'policy_directory' => 'Policies',
    ],

    'exclude' => [
        'enabled' => true,

        'pages' => [
            'Dashboard',
        ],

        'widgets' => [
            'AccountWidget',
        ],

        'resources' => [
        ],
    ],

    'discovery' => [
        'discover_all_resources' => false,
        'discover_all_widgets' => false,
        'discover_all_pages' => false,
    ],

    'register_role_policy' => [
        'enabled' => false,
    ],

];
