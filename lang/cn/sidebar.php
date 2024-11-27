<?php   
return [
    'module' => [
        [
            'title' => 'QL Sản phẩm',
            'icon' => 'fa fa-box',
            'name' => ['user','permission'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm Sản phẩm',
                    'route' => 'product/catalouge/index'
                ],
                [
                    'title' => 'QL Sản phẩm',
                    'route' => 'product/index'
                ],
                [
                    'title' => 'QL Nhóm thuộc tính',
                    'route' => 'attribute/catalouge/index'
                ],
                [
                    'title' => 'QL Thuộc tính',
                    'route' => 'attribute/index'
                ],
            ]
        ],
        [
            'title' => '用户组',  // User Group
            'icon' => 'fa fa-user',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => '用户组',  // User Group
                    'route' => 'user/catalouge/index'
                ],
                [
                    'title' => '用户',  // User
                    'route' => 'user/index'
                ],
                [
                    'title' => '允许',
                    'route' => 'permission/index'
                ],
            ]
        ],
        [
            'title' => '文章',  // Article
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => '文章组',  // Article Group
                    'route' => 'post/catalouge/index'
                ],
                [
                    'title' => '文章',  // Article
                    'route' => 'post/index'
                ]
            ]
        ],
        [
            'title' => '通用',  // General
            'icon' => 'fa fa-gear',
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => '语言',  // Language
                    'route' => 'language/index'
                ],
                [
                    'title' => '模块',
                    'route' => 'generate/index'
                ],
            ]
        ]
    ],
];


