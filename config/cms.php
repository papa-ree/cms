<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mandatory Meta Schema (Hero Baseline)
    |--------------------------------------------------------------------------
    | Semua section HARUS memiliki key ini.
    | Theme dan editor boleh berasumsi key ini selalu ada.
    */

    'mandatory_meta' => [

        'title' => [
            'type' => 'string',
            'default' => '',
            'label' => 'Title',
        ],

        'subtitle' => [
            'type' => 'string',
            'default' => '',
            'label' => 'Subtitle',
        ],

        'buttons' => [
            'type' => 'array',
            'label' => 'Buttons',
            'schema' => [
                'label' => [
                    'type' => 'string',
                    'default' => '',
                ],
                'url' => [
                    'type' => 'string',
                    'default' => '',
                ],
                'show' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
            ],
        ],

        'background' => [
            'type' => 'object',
            'label' => 'Background',
            'schema' => [
                'type' => [
                    'type' => 'enum',
                    'options' => ['image', 'slider'],
                    'default' => 'image',
                ],
                'images' => [
                    'type' => 'array',
                    'item' => [
                        'path' => 'string',
                        'url' => 'string',
                        'disk' => 'string',
                    ],
                    'default' => [],
                ],
            ],
        ],

        'alignment' => [
            'type' => 'enum',
            'options' => ['left', 'center', 'right'],
            'default' => 'center',
        ],

        'theme' => [
            'type' => 'enum',
            'options' => ['light', 'dark'],
            'default' => 'light',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Section Schemas
    |--------------------------------------------------------------------------
    | Semua section mewarisi mandatory_meta.
    | Key tambahan harus masuk ke `custom`.
    */

    'sections' => [

        /*
        |--------------------------------------------------
        | HERO (FULL MANDATORY)
        |--------------------------------------------------
        */
        'hero' => [
            'label' => 'Hero Section',
            'meta' => [
                'mandatory' => true,
                'custom' => [],
            ],
            'items' => [],
        ],

        /*
        |--------------------------------------------------
        | POST
        |--------------------------------------------------
        */
        'post' => [
            'label' => 'Post Section',
            'meta' => [
                'mandatory' => true,
                'custom' => [
                    'post_limit' => [
                        'type' => 'integer',
                        'default' => 6,
                        'label' => 'Post Limit',
                    ],
                    'show_excerpt' => [
                        'type' => 'boolean',
                        'default' => true,
                        'label' => 'Show Excerpt',
                    ],
                ],
            ],
            'items' => [],
        ],

        /*
        |--------------------------------------------------
        | FOOTER
        |--------------------------------------------------
        */
        'footer' => [
            'label' => 'Footer',
            'meta' => [
                'mandatory' => true,
                'custom' => [
                    'copyright' => [
                        'type' => 'string',
                        'default' => '',
                        'label' => 'Copyright Text',
                    ],
                    'show_social' => [
                        'type' => 'boolean',
                        'default' => true,
                        'label' => 'Show Social Media',
                    ],
                ],
            ],
            'items' => [],
        ],

        /*
        |--------------------------------------------------
        | FEATURES / SERVICES
        |--------------------------------------------------
        */
        'features' => [
            'label' => 'Features / Services',
            'meta' => [
                'mandatory' => true,
                'custom' => [
                    'columns' => [
                        'type' => 'integer',
                        'default' => 3,
                        'label' => 'Columns',
                    ],
                    'icon_style' => [
                        'type' => 'enum',
                        'options' => ['outline', 'solid'],
                        'default' => 'outline',
                    ],
                ],
            ],
            'items' => [
                'title' => 'string',
                'description' => 'string',
                'icon' => 'string',
            ],
        ],

        /*
        |--------------------------------------------------
        | STATISTICS
        |--------------------------------------------------
        */
        'statistics' => [
            'label' => 'Statistics',
            'meta' => [
                'mandatory' => true,
                'custom' => [
                    'number_format' => [
                        'type' => 'string',
                        'default' => 'short',
                    ],
                ],
            ],
            'items' => [
                'label' => 'string',
                'value' => 'integer',
                'suffix' => 'string',
            ],
        ],

        /*
        |--------------------------------------------------
        | CTA
        |--------------------------------------------------
        */
        'cta' => [
            'label' => 'Call To Action',
            'meta' => [
                'mandatory' => true,
                'custom' => [
                    'background_style' => [
                        'type' => 'enum',
                        'options' => ['gradient', 'image'],
                        'default' => 'gradient',
                    ],
                ],
            ],
            'items' => [],
        ],

    ],

];