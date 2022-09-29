<?php

return [

    //全局请求过滤
    'request_safe' => [
        'global' => true,
        'type' => 'json',
        'replace' => [
            '$_REQUEST' => true,
            '$_POST' => true,
            '$_GET' => false,
        ],
    ],

    //通用工程相关
    'lang_path' => realpath(APP_PATH . '../../common/lang'),      //语言包路径
    'theme_path' => SYS_ENV['THEME_PATH'],                              //主题路径

    //fontawesome字体图标库路径
    'fontawesome_path' => realpath(APP_PATH . '../../common/other/fontawesome.json'),

    //状态
    'user_status' => [0, 1, 2],  //用户状态
    'role_status' => [0, 1, 2],  //角色状态
    'menu_status' => [0, 1, 2],  //菜单状态
    'log_level' => [0, 1, 2],    //日志状态

    //restful支持方法
    'restful_method' => ['get', 'post', 'put', 'delete'],

    //演示模式禁用API
    'demo_not_allow' => [
        'clear_cache',
        'change_userinfo',
        'add',
        'save',
        'status_change',
        'add_role',
        'edit_role',
        'create_user',
        'info_change',
    ],
];
