<?php

if($_SERVER['IS_DOCKER']) {
    $db_host = SYS_ENV['DOCKER_MYSQL_HOST'];
}else {
    $db_host = SYS_ENV['MYSQL_HOST'] ? : '127.0.0.1';
}

return [
    'layout_on'       => true,
    'layout'          => false,

    // 数据库配置
    'db_type'         => 'mysql',
    'db_host'         => $db_host,
    'db_name'         => SYS_ENV['MYSQL_DBNM'] ? : 'vuePoem',
    'db_user'         => SYS_ENV['MYSQL_USER'] ? : 'root',
    'db_pass'         => SYS_ENV['MYSQL_PASS'] ? : '',
    'db_prefix'       => @SYS_ENV['MYSQL_PREFIX'] ? : 'poem_',
    'db_port'         => @SYS_ENV['MYSQL_PORT'] ? : '3306',
    'db_charset'      => @SYS_ENV['MYSQL_CHARSET'] ? : 'utf8mb4',
    'db_dsn'          => @SYS_ENV['MYSQL_DSN'] ? : '',
    'db_deploy'       => @SYS_ENV['MYSQL_DEPLOY'] ? : false, // 部署方式: false 集中式(单一服务器),true 分布式(主从服务器)
    'db_rw_separate'  => @SYS_ENV['MYSQL_RW_SEPARATE'] ? : false, // 数据库读写是否分离 主从式有效
    'db_master_num'   => @SYS_ENV['MYSQL_MASTER_NUM'] ? : 1, // 读写分离后 主服务器数量
    'db_slave_no'     => @SYS_ENV['MYSQL_SLAVE_NO'] ? : '', // 指定从服务器序号

    'session_type'    => '',
    'session_prefix'  => '',
    'default_module'   => 'admin', // 默认模块
    'default_errcode'   => 1, // 默认ajax 错误码，gp()使用

    'cookie_expire'   => 0,
    'cookie_domain'   => '',
    'cookie_path'     => '/',
    'cookie_prefix'   => '',
    'cookie_secure'   => false, // cookie 启用安全传输 true 在https下会传输，http不会传输
    'cookie_httponly' => false, // true 无法通过程序读取如 JS脚本、Applet等

    'log_path'        => '', // 日志路径
    'log_level'       => 5,
    'log_remain_days'  => 1, // 日志保留天数

    'debug_trace'  => false, // 页面右下角展示调试信息
];
