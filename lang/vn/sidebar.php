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
            ]
        ],
        [
            'title' => 'QL Người dùng',
            'icon' => 'fa fa-user',
            'name' => ['user','permission'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm Thành Viên',
                    'route' => 'user/catalouge/index'
                ],
                [
                    'title' => 'QL Thành Viên',
                    'route' => 'user/index'
                ],
                [
                    'title' => 'QL Quyền',
                    'route' => 'permission/index'
                ],
            ]
        ],
        [
            'title' => 'QL Bài viết',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm Bài Viết',
                    'route' => 'post/catalouge/index'
                ],
                [
                    'title' => 'QL Bài Viết',
                    'route' => 'post/index'
                ]
            ]
        ],
        [
            'title' => 'Cấu hình chung',
            'icon' => 'fa fa-gear',
            'name' => ['language','generate'],
            'subModule' => [
                [
                    'title' => 'QL Ngôn ngữ',
                    'route' => 'language/index'
                ],
                [
                    'title' => 'QL Module',
                    'route' => 'generate/index'
                ],
                
            ]
        ]
    ],
];
