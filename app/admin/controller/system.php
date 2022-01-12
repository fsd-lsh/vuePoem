<?php

namespace admin\controller;

use service\component;

/**
 * Class system
 * @package admin\controller
 */
class system extends component\login {

    /**
     * system constructor.
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct(1, 'admin');
    }

    /**
     * Func: index
     * User: Force
     * Date: 2021/9/27
     * Time: 20:23
     * Desc: 系统监控
     */
    public function index() {

        //API
        sys_api([

            //加载系统监控信息
            'load' => function() {

                //系统信息
                $data['sys'] = [
                    'os' => PHP_OS,
                    'php' => PHP_VERSION . '-' . php_sapi_name(),
                    'zend'  => zend_version(),
                ];
                $data['sys'] = array_merge($data['sys'], $_SERVER);

                //CPU
                exec('cat /proc/cpuinfo | head -20', $data['cpu']);

                //内存
                exec('cat /proc/meminfo | head -20', $data['ram']);

                //磁盘
                exec('df -h', $data['hdd']);

                //响应
                ajax(1, '加载成功', $data);
            }
        ]);

        //渲染视图
        vue();
    }
}
