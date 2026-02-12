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
        'banner' => [
            'label' => 'Banner Section',
            'description' => 'Section dengan background visual utama dan tombol aksi.',
            'icon' => 'dock',
            'type' => 'single',
            'meta' => [
                'mandatory' => true,
                'custom' => [
                    'organization_name' => [
                        'type' => 'string',
                        'default' => 'Bale Content Management',
                        'label' => 'Organization Name',
                    ],
                ],
            ],
            'items' => [],
        ],

        /*
        |--------------------------------------------------
        | POST
        |--------------------------------------------------
        */
        'collection' => [
            'label' => 'Collection Section',
            'description' => 'Menampilkan daftar konten dalam bentuk grid atau list.',
            'icon' => 'layout-list',
            'type' => 'listing',
            'meta' => [
                'mandatory' => true,
                'custom' => [
                    'post_limit' => [
                        'type' => 'integer',
                        'default' => 3,
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
        'link-group' => [
            'label' => 'Link Group Section',
            'description' => 'Menampilkan beberapa kelompok link navigasi.',
            'icon' => 'link',
            'type' => 'navigation',
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
        'blocks' => [
            'label' => 'Blocks Section',
            'description' => 'Section dengan beberapa blok konten berulang.',
            'icon' => 'layout-grid',
            'type' => 'repeatable',
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
                        'label' => 'Icon Style',
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
        'metrics' => [
            'label' => 'Metrics Section',
            'description' => 'Menampilkan angka atau data statistik.',
            'icon' => 'bar-chart-3',
            'type' => 'repeatable',
            'meta' => [
                'mandatory' => true,
                'custom' => [
                    'number_format' => [
                        'type' => 'string',
                        'default' => 'short',
                        'label' => 'Number Format',
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
        'highlight' => [
            'label' => 'Highlight Section',
            'description' => 'Section penekanan dengan tombol aksi.',
            'icon' => 'megaphone',
            'type' => 'single',
            'meta' => [
                'mandatory' => true,
                'custom' => [
                    'background_style' => [
                        'type' => 'enum',
                        'options' => ['gradient', 'image'],
                        'default' => 'gradient',
                        'label' => 'Background Style',
                    ],
                ],
            ],
            'items' => [],
        ],

    ],

];