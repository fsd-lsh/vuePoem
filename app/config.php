<?php

return [

    //语言包路径
    'lang_path' => realpath('./../app/admin/vue/src/assets/languages'),

    //状态
    'status' => [
        0 => '删除',
        1 => '正常',
        2 => '暂停',
    ],

    //用户状态
    'user_status' => [
        0 => '删除',
        1 => '正常',
        2 => '冻结'
    ],

    //角色状态
    'role_status' => [
        0 => '删除',
        1 => '正常',
        2 => '暂停',
    ],

    //菜单状态
    'menu_status' => [
        0 => '删除',
        1 => '启用',
        2 => '停用',
    ],

    //日志状态
    'log_level' => [
        0 => '普通日志',
        1 => '警告日志',
        2 => '高危日志',
    ],

    //restful支持方法
    'restful_method' => [
        'get',
        'post',
        'put',
        'delete',
    ],
];
