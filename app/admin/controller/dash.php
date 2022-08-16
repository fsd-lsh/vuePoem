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

                $info = trans('admin.dash.cleanOk');

                if(file_exists($cache_view) && 0) {
                    exec('rm -rf ' . $cache_view);
                    unlink($cache_view);
                    $info .= ', ' . trans('admin.dash.cleanViewOK');
                }else {
                    $info .= ', ' . trans('admin.dash.cleanViewFail');
                }

                if(file_exists($cache_log)) {
                    exec('rm -rf ' . $cache_log);
                    unlink($cache_log);
                    $info .= ', ' . trans('admin.dash.cleanLogOK');
                }else {
                    $info .= ', ' . trans('admin.dash.cleanLogFail');
                }

                ajax(1, $info);
            },
        ]);
    }

    /**
     * Func: main
     * User: Force
     * Date: 2021/4/7
     * Time: 20:53
     * Desc: 后台默认面板
     */
    public function main() {

        //API
        sys_api([

            //加载面板数据
            'load' => function() {

                //后台用户统计
                $admin_total = [];
                $admin_config = config('user_status');
                if(is_array($admin_config) && !empty($admin_config)) {
                    foreach ($admin_config as $status) {
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
                    foreach ($role_config as $status) {
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
                    foreach ($menu_config as $status) {
                        $menu_total[$status] = m('sys_menu')
                            ->field('count(1) as total')
                            ->where([
                                'status' => $status,
                            ])
                            ->find()['total'];
                    }
                }

                //后台运行日志您没有访问权限统计
                $log_total = [];
                $log_config = config('log_level');
                if(is_array($log_config) && !empty($log_config)) {
                    foreach ($log_config as $level) {
                        $log_total[$level] = m('sys_log')
                            ->field('count(1) as total')
                            ->where([
                                'level' => $level,
                            ])
                            ->find()['total'];
                    }
                }

                //渲染视图
                ajax(1, trans('admin.dash.loadPanelOk'), [
                    'admin_total' => $admin_total,
                    'role_total' => $role_total,
                    'menu_total' => $menu_total,
                    'log_total' => $log_total,
                ]);
            },
        ]);
    }

    /**
     * Func: userInfo
     * User: Force
     * Date: 2021/9/20
     * Time: 16:58
     * Desc: 修改用户信息
     */
    public function userInfo() {

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
                        'id' => ['!=', $_SESSION['admin_info']['id']],
                    ])
                    ->find();
                if($check) {
                    ajax(0, trans('admin.dash.hasAccFail'));
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
                    $this->log($_SESSION['admin_info']['name'].', '.trans('admin.dash.userInfoModOk'));
                    ajax(1, trans('admin.dash.userInfoModOk'));
                }else {
                    $this->log($_SESSION['admin_info']['name'].', '.trans('admin.dash.userInfoModFail'));
                    ajax(0, trans('admin.dash.userInfoModFail'));
                }
            },

            //加载用户信息
            'load' => function() {

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
                ajax(1, trans('admin.dash.loadUserInfoOk'), [
                    'roles_config' => $roles_info,
                    'user_info' => $user_info,
                ]);
            },
        ]);
    }
}
