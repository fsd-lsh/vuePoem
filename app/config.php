<?php

return [

    //语言包路径
    'lang_path' => realpath('./../app/admin/vue/src/assets/languages'),

    //fontawesome字体图标库路径
    'fontawesome_path' => realpath('./../public/static/other/fontawesome.json'),

    //状态
    'user_status' => [0, 1, 2],  //用户状态
    'role_status' => [0, 1, 2],  //角色状态
    'menu_status' => [0, 1, 2],  //菜单状态
    'log_level' => [0, 1, 2],    //日志状态

    //restful支持方法
    'restful_method' => ['get', 'post', 'put', 'delete'],
];
