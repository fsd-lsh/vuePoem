<?php

namespace admin\controller;

use service\middleware;

/**
 * Class log
 * @package admin\controller
 */
class log extends middleware\login {

    private $log_path = APP_RUNTIME_PATH . 'log';

    /**
     * log constructor.
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct(1, 'admin');
    }

    /**
     * Func: fwLog
     * User: Force
     * Date: 2022/10/12
     * Time: 11:15
     * Desc: 框架日志
     */
    public function fwLog() {

        //API
        sys_api([

            'load' => function() {

                $max_module = 5;
                $dir = scandir($this->log_path);
                unset(
                    $dir[array_search('.', $dir)],
                    $dir[array_search('..', $dir)]
                );
                if(count($dir) > $max_module) {
                    $dir = array_slice($dir, 0, $max_module);
                }

                $lists = [];
                foreach ($dir as $dir_name) {

                    $log_file = $this->log_path . '/' . $dir_name;
                    $log_file = scandir($log_file);
                    unset(
                        $log_file[array_search('.', $log_file)],
                        $log_file[array_search('..', $log_file)]
                    );
                    $log_file = array_slice($log_file, 0, 50);

                    foreach ($log_file as $log_name) {

                        $lists[] = [
                            'module_name' => $dir_name,
                            'log_name' => $log_name,
                            'log_date' => date('Y-m-d', strtotime(substr($log_name, 0, 8))),
                            'log_hour' => substr($log_name, 8, 2),
                        ];
                    }
                }

                $lists = array_order_by($lists, 'log_name', SORT_DESC);
                $lists = array_values($lists);

                ajax(1, trans('admin.sysLog.loadListOk'), $lists);
            },

            'open_log' => function() {

                $log_path = $this->log_path . '/' . $_POST['module_name'] . '/' . $_POST['log_name'];
                if(!file_exists($log_path)) {
                    ajax(0, trans('admin.fwLog.logLost'));
                }

                $temp = [];
                $log_detail = file_get_contents($log_path);
                $log_detail = explode("\n", $log_detail);
                $log_detail = array_filter($log_detail);

                foreach ($log_detail as $item_log) {

                    $level = '';
                    if(is_numeric(strpos($item_log,'[INFO]'))) {
                        $item_log = str_replace('[INFO] ', '', $item_log);
                        $level = 1;
                    }
                    if(is_numeric(strpos($item_log,'[FATAL]'))) {
                        $item_log = str_replace('[FATAL] ', '', $item_log);
                        $level = 0;
                    }

                    $log_time = substr($item_log, 0, 19);
                    $item_log = str_replace($log_time.' ', '', $item_log);
                    $item_log = str_replace(VUEPOEM_ROOT, '', $item_log);

                    $temp[] = [
                        'info' => $item_log,
                        'level' => $level,
                        'log_time' => $log_time,
                    ];
                }
                $log_detail = $temp;
                unset($temp);

                $log_detail = array_order_by($log_detail, 'log_time', SORT_DESC);
                $log_detail = array_values($log_detail);

                ajax(1, trans('admin.sysLog.loadListOk'), $log_detail);
            },
        ]);
    }

    /**
     * Func: sysLog
     * User: Force
     * Date: 2021/9/1
     * Time: 20:53
     * Desc: 系统日志
     */
    public function sysLog() {

        //API
        sys_api([

            //加载列表
            'load' => function() {

                $log_data = m('sys_log');
                $_POST['pageSize'] = $_POST['pageSize'] ? intval($_POST['pageSize']) : 15;

                //组装条件
                $where = [];
                if(is_numeric($_POST['status'])) {
                    $where['level'] = intval($_POST['status']);
                }
                if(!empty($_POST['id'])) {
                    $where['id'] = intval($_POST['id']);
                }
                if(!empty($_POST['log'])) {
                    $log_data->where("log like '%{$_POST['log']}%'");
                }
                if(!empty($_POST['path'])) {
                    $log_data->where("path like '%{$_POST['path']}%'");
                }
                if(!empty($_POST['ctime'])) {
                    $_POST['ctime'][0] = substr($_POST['ctime'][0], 0, 10);
                    $_POST['ctime'][1] = substr($_POST['ctime'][1], 0, 10);
                    $log_data->where("ctime >= '{$_POST['ctime'][0]}' && ctime <= '{$_POST['ctime'][1]}'");
                }

                //获取日志
                $log_data
                    ->where($where)
                    ->order('id desc');

                //分页
                $log_data = \poem\more\page::run($log_data, '/#/log/sysLog', $_POST['pageSize']);

                //数据格式化
                if(is_array($log_data['list']) && !empty($log_data['list'])) {

                    //获取后台用户信息
                    $admin_info = m('sys_admin')
                        ->field('id, name')
                        ->where([
                            'status' => 1,
                        ])
                        ->select();
                    if($admin_info) {
                        $admin_info = array_combine(array_column($admin_info, 'id'), $admin_info);
                    }

                    foreach ($log_data['list'] as $key => $item) {
                        $log_data['list'][$key]['admin'] = $admin_info[$item['admin_id']]['name'];
                        $log_data['list'][$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);
                        $log_data['list'][$key]['level_mean'] = trans('admin.sysLog.levelMean'.$item['level']);
                    }
                }

                //response
                ajax(1, trans('admin.sysLog.loadListOk'), [
                    'lists' => $log_data['list'],
                    'page_html' => $log_data['html'],
                ]);
            },
        ]);
    }
}
