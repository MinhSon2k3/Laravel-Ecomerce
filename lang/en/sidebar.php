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
            'title' => 'User',
            'icon' => 'fa fa-user',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => 'User Group',
                    'route' => 'user/catalouge/index'
                ],
                [
                    'title' => 'User',
                    'route' => 'user/index'
                ],
                [
                    'title' => 'Permission',
                    'route' => 'permission/index'
                ],
            ]
        ],
        [
            'title' => 'Article',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Article Group',
                    'route' => 'post/catalouge/index'
                ],
                [
                    'title' => 'Article',
                    'route' => 'post/index'
                ]
            ]
        ],
        [
            'title' => 'General',
            'icon' => 'fa fa-gear',
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => 'Language',
                    'route' => 'language/index'
                ],
                [
                    'title' => 'Module',
                    'route' => 'generate/index'
                ],
            ]
        ]
    ],
];

