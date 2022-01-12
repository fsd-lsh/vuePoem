<?php

namespace admin\controller;

use \service\component;

/**
 * Class dash
 * @package admin\controller
 */
class dash extends component\login {

    /**
     * dash constructor.
     */
    public function __construct() {
        parent::__construct(1, 'admin');
    }

    /**
     * Func: index
     * User: Force
     * Date: 11/28/20
     * Time: 9:40 PM
     * Desc: 后台界面主体框架
     */
    public function index() {

        //API
        sys_api([

            //清理后台视图缓存
            'clear_cache' => function() {

                $cache_view = APP_RUNTIME_PATH . 'admin';
                $cache_log = APP_RUNTIME_PATH . 'log/admin';

                $info = '执行清理缓存完成';

                if(file_exists($cache_view)) {
                    exec('rm -rf ' . $cache_view);
                    unlink($cache_view);
                    $info .= '，视图缓存清理成功';
                }else {
                    $info .= '，视图缓存清理失败';
                }

                if(file_exists($cache_log)) {
                    exec('rm -rf ' . $cache_log);
                    unlink($cache_log);
                    $info .= '，日志缓存清理成功';
                }else {
                    $info .= '，日志缓存清理失败';
                }

                echo json_encode(['code' => 1, 'msg' => $info]); exit;
            },
        ]);

        //渲染视图
        v();
    }

    /**
     * Func: main
     * User: Force
     * Date: 2021/4/7
     * Time: 20:53
     * Desc: 后台默认面板
     */
    public function main() {

        //后台用户统计
        $admin_total = [];
        $admin_config = config('user_status');
        if(is_array($admin_config) && !empty($admin_config)) {
            foreach ($admin_config as $status => $mean) {
                $admin_total[$status] = m('sys_admin')
                    ->field('count(1) as total')
                    ->where([
                        'status' => $status,
                    ])
                    ->find()['total'];
            }
        }

        //后台角色统计
        $role_total = [];
        $role_config = config('role_status');
        if(is_array($role_config) && !empty($role_config)) {
            foreach ($role_config as $status => $mean) {
                $role_total[$status] = m('sys_roles')
                    ->field('count(1) as total')
                    ->where([
                        'status' => $status,
                    ])
                    ->find()['total'];
            }
        }

        //后台菜单统计
        $menu_total = [];
        $menu_config = config('menu_status');
        if(is_array($menu_config) && !empty($menu_config)) {
            foreach ($menu_config as $status => $mean) {
                $menu_total[$status] = m('sys_menu')
                    ->field('count(1) as total')
                    ->where([
                        'status' => $status,
                    ])
                    ->find()['total'];
            }
        }

        //后台运行日志统计
        $log_total = [];
        $log_config = config('log_level');
        if(is_array($log_config) && !empty($log_config)) {
            foreach ($log_config as $level => $mean) {
                $log_total[$level] = m('sys_log')
                    ->field('count(1) as total')
                    ->where([
                        'level' => $level,
                    ])
                    ->find()['total'];
            }
        }

        //渲染视图
        assign([
            'admin_total' => json_encode($admin_total),
            'role_total' => json_encode($role_total),
            'menu_total' => json_encode($menu_total),
            'log_total' => json_encode($log_total),
        ]);
        vue();
    }

    /**
     * Func: user_info
     * User: Force
     * Date: 2021/9/20
     * Time: 16:58
     * Desc: 修改用户信息
     */
    public function user_info() {

        //API
        sys_api([

            //修改用户信息
            'change_userinfo' => function() {

                //接收请求
                $name = gp('name');
                $password = i('password');

                //检查账号
                $check = m('sys_admin')
                    ->where([
                        'status' => 1,
                        'name' => $name,
                    ])
                    ->find();
                if($check) {
                    ajax(0, '账号已存在，请更换再试');
                }

                //组装数据
                $data = [
                    'name' => $name,
                    'utime' => time(),
                ];
                if($password) {
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }

                //更新
                $result = m('sys_admin')
                    ->where([
                        'id' => $_SESSION['admin_info']['id'],
                        'status' => 1,
                    ])
                    ->update($data);

                //响应
                if($result) {
                    $this->log($_SESSION['admin_info']['name'].', 用户信息修改完成');
                    ajax(1, '用户信息修改完成');
                }else {
                    $this->log($_SESSION['admin_info']['name'].', 用户信息修改失败');
                    ajax(0, '用户信息修改失败');
                }
            },
        ]);

        //获取账号信息
        $user_info = m('sys_admin')
            ->where([
                'id' => $_SESSION['admin_info']['id'],
                'status' => 1,
            ])
            ->find();

        //获取角色信息
        $roles_info = m('sys_roles')
            ->where([
                'status' => 1,
            ])
            ->select();
        if(is_array($roles_info) && !empty($roles_info)) {
            $roles_info = array_combine(array_column($roles_info, 'id'), $roles_info);
        }

        //数据格式化
        $user_info['roles'] = explode(',', $user_info['roles']);
        $user_info['status'] = config('user_status')[$user_info['status']];
        $user_info['ctime'] = date('Y-m-d H:i:s', $user_info['ctime']);
        $user_info['utime'] = date('Y-m-d H:i:s', $user_info['utime']);
        unset($user_info['password']);

        //渲染视图
        assign([
            'roles_config' => json_encode($roles_info),
            'user_info' => json_encode($user_info),
        ]);
        vue();
    }
}
