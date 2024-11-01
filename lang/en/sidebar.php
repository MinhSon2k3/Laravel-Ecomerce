<?php   
return [
    'module' => [
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
                    'title' => 'QL Quyền',
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
            'icon' => 'fa fa-file',
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => 'Language',
                    'route' => 'language/index'
                ],
            ]
        ]
    ],
];

