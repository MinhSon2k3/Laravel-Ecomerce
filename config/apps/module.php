<?php

return [
    'module' => [
        [
            'title' => 'Quản lý người dùng',
            'icon'  => '<i class="fa fa-user"></i>',
            'name'  => 'user',
            'subModule' => [
                [
                    'title' => 'Quản lý nhóm thành viên',
                    'route' => 'user/catalouge/index'
                ],
                [
                    'title' => 'Quản lý thành viên',
                    'route' => 'user/index'
                ],
            ]
        ],
        [
            'title' => 'Quản lý bài viết',
            'icon'  => '<i class="fa fa-newspaper-o"></i>',
            'name'  => 'post',
            'subModule' => [
                [
                    'title' => 'Quản lý nhóm bài viết',
                    'route' => 'post/catalouge/index'
                ],
                [
                    'title' => 'Quản lý bài viết',
                    'route' => 'post/index'
                ],
               
            ]
        ],

        [
            'title' => 'Cấu hình chung',
            'icon'  => '<i class="fa fa-solid fa-gear"></i>',
            'name'  => 'language',
            'subModule' => [
                [
                    'title' => 'Quản lý ngôn ngữ',
                    'route' => 'language/index'
                ],
               
            ]
        ]

        
        
    ],
];