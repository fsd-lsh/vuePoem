<?php

namespace service\component;

/**
 * Class login
 * 登录服务套件（前后台通用）
 */
class login {

    private $user_info;
    private $user_type;

    /**
     * login constructor.
     * @throws \Exception
     */
    public function __construct($check_login = 0, $user_type = 'user') {

        //装载
        $this->user_type = $user_type;
        $this->user_info = session($this->user_type.'_info');

        //登录检测
        if($check_login) {

            switch ($this->user_type) {
                case 'user': { $url = '/'; break; }
                case 'admin': { $url = '/admin'; break; }
            }

            if(!$this->check_login()) {
                ajax(0, '您未登录', $url);
            }

            $this->authentication();
        }
    }

    /**
     * Func: check_login
     * User: Force
     * Date: 2020/9/3
     * Time: 20:37
     * Desc: 检查登录状态
     */
    public function check_login() {

        return (!empty($this->user_info) && is_array($this->user_info)) ? true : false;
    }

    /**
     * Func: sign_in
     * User: Force
     * Date: 2021/4/4
     * Time: 18:49
     * Desc: 登录
     */
    public function sign_in() {

        //检查请求
        if(empty(i('username'))) {
            ajax(0, '请输入账号');
        }
        if(empty(i('password'))) {
            ajax(0, '请输入密码');
        }

        //获取账号信息
        switch ($this->user_type) {
            case 'user': { $table = 'user'; break; }
            case 'admin': { $table = 'sys_admin'; break; }
        }
        $user_info = m($table)
            ->where([
                'name' => i('username'),
                'status' => 1
            ])
            ->find();
        if(empty($user_info)) {
            ajax(0, '账号不存在或已被冻结');
        }

        //检查密码
        if(!password_verify(i('password'), $user_info['password'])) {
            ajax(0, '密码错误');
        }

        //写入登录信息
        session($this->user_type.'_info', $user_info);

        //登录跳转至后台
        switch ($this->user_type) {
            case 'user': { $url = '/'; break; }
            case 'admin': { $url = '/dash'; break; }
        }
        $this->log('用户'.$_SESSION['admin_info']['name'].', 登录成功');
        ajax(1, '登录成功', [
            'url' => $url,
            'name' => $_SESSION['admin_info']['name'],
        ]);
    }

    /**
     * Func: sign_up
     * User: Force
     * Date: 2020/10/5
     * Time: 17:23
     * Desc: 注册
     */
    public function sign_up() {

    }

    /**
     * Func: sign_out
     * User: Force
     * Date: 2020/9/3
     * Time: 20:34
     * Desc: 注销登录
     */
    public function sign_out() {

        switch ($this->user_type) {
            case 'user': { $url = '/'; break; }
            case 'admin': { $url = '/'; break; }
        }
        session($this->user_type.'_info', NULL);
        ajax(1, '注销登陆成功', $url);
    }

    /**
     * Func: log
     * User: Force
     * Date: 2021/9/11
     * Time: 14:22
     * Desc: 后台日志记录
     */
    public function log($info = NULL, $level = 0) {

        if(!$info) {
            return false;
        }

        m('sys_log')
            ->insert([
                'admin_id' => intval($_SESSION['admin_info']['id']),
                'log' => $info,
                'path' => $_SERVER['REQUEST_URI'],
                'ctime' => time(),
                'level' => $level,
            ]);
    }

    /**
     * Func: authentication
     * User: Force
     * Date: 2021/9/10
     * Time: 22:48
     * Desc: 后台用户鉴权
     */
    private function authentication() {

        //获取用户信息
        $user_info = m('sys_admin')
            ->where([
                'id' => intval($_SESSION['admin_info']['id']),
                'status' => 1,
            ])
            ->find();
        if(!$user_info) {
            ajax(0, '您的账号不存在或已被冻结');
        }
        if(!$user_info['roles']) {
            ajax(0, '您的账号没有系统角色，请联系系统管理员分配后再试');
        }

        //获取用户角色
        $role_info = m('sys_roles')
            ->where('id in (' . $user_info['roles'] . ')')
            ->where([
                'status' => 1,
            ])
            ->select();
        if(!is_array($role_info) || empty($role_info)) {
            ajax(0, '您的角色不存在');
        }

        //获取菜单权限
        $menu_ids = [];
        foreach ($role_info as $key => $item) {
            $item['menu_ids'] = explode(',', $item['menu_ids']);
            $menu_ids = array_merge($menu_ids, $item['menu_ids']);
        }
        if(!is_array($menu_ids) || empty($menu_ids)) {
            ajax(0, '角色没有配置菜单权限');
        }
        $menu_ids = array_filter($menu_ids);
        $menu_ids = array_unique($menu_ids);

        //获取菜单信息
        $menu_info = m('sys_menu')
            ->where('id in (' . implode(',', $menu_ids) . ') and pid != 0')
            ->where([
                'status' => 1,
            ])
            ->select();
        if(!is_array($menu_info) || empty($menu_info)) {
            ajax(0, '您权限内菜单不存在或无效');
        }

        //组装访问白名单
        $menu_path = array_column($menu_info, 'href');
        $menu_path = array_filter($menu_path);
        $menu_path = array_unique($menu_path);
        $menu_path = array_merge($menu_path, [
            '/dash',
            '/dash/user_info',
            '/menu/load',
            '/dash/main',
        ]);

        //检查用户权限
        $url_path = $_SERVER['PATH_INFO'];
        if(!$url_path) {
            ajax(0, '您的请求无效');
        }

        $url_path = str_replace('/admin', '', $url_path);
        if(!in_array($url_path, $menu_path)) {
            ajax(0, '您没有访问权限');
        }
    }

    /**
     * login __deconstruct
     */
    public function __deconstruct() {}
}
