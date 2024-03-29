<?php

namespace service\middleware;

/**
 * Class login
 * 登录服务
 */
class login {

    private $user_info;
    private $user_type;
    private string $table;

    /**
     * login constructor.
     * @throws \Exception
     */
    public function __construct($check_login = 0, $user_type = 'user') {

        $this->user_type = $user_type;
        $this->user_info = session($this->user_type.'_info');


        if($check_login) {

            switch ($this->user_type) {
                case 'user': { $url = '/'; break; }
                case 'admin': { $url = '/admin'; break; }
            }

            if(!$this->check_login()) {
                $info = trans('admin.signIn.noSignIn');
                ajax(955, $info ? : 'Not logged in', $url);
            }

            $this->authentication();
        }

        switch ($this->user_type) {
            case 'user': { $this->table = 'user'; break; }
            case 'admin': { $this->table = 'sys_admin'; break; }
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
     * @throws \Exception
     */
    public function sign_in() {

        //检查请求
        if(empty(i('username'))) {
            ajax(0, trans('admin.signIn.enterAcc'));
        }
        if(empty(i('password'))) {
            ajax(0, trans('admin.signIn.enterPwd'));
        }
        $username = base64_decode(i('username'));
        $password = base64_decode(i('password'));
        $v_code = i('vCode');
        if(empty(json_encode($username))) {
            ajax(0, trans('admin.signIn.enterAcc'));
        }
        if(empty(json_encode($password))) {
            ajax(0, trans('admin.signIn.enterPwd'));
        }
        if(empty($v_code)) {
            ajax(0, trans('admin.signIn.enterVCode'));
        }
        if($v_code != session('verify_code')) {
            ajax(0, trans('admin.signIn.vCodeErr'));
        }

        //获取账号信息
        $user_info = m($this->table)
            ->where([
                'name' => $username,
                'status' => 1
            ])
            ->find();
        if(empty($user_info)) {
            ajax(0, trans('admin.signIn.accNoFound'));
        }

        //检查密码
        if(!password_verify($password, $user_info['password'])) {
            ajax(0, trans('admin.signIn.pwdErr'));
        }

        //写入登录信息
        session($this->user_type.'_info', $user_info);

        //登录跳转至后台
        switch ($this->user_type) {
            case 'user': { $url = '/'; break; }
            case 'admin': { $url = '/dash'; break; }
        }
        $this->log(trans('admin.signIn.user').$_SESSION['admin_info']['name'].', '.trans('admin.signIn.signInOk'));
        ajax(1, trans('admin.signIn.signInOk'), [
            'url' => $url,
            'name' => $_SESSION['admin_info']['name'],
        ]);
    }

    /**
     * Func: sign_up
     * User: Force
     * Date: 2023/3/3
     * Time: 11:57
     * Desc: 注册
     */
    public function sign_up() {

        if(empty(i('username'))) {
            ajax(0, trans('admin.signIn.enterAcc'));
        }
        if(empty(i('password'))) {
            ajax(0, trans('admin.signIn.enterPwd'));
        }
        $username = base64_decode(i('username'));
        $password = base64_decode(i('password'));
        $re_password = base64_decode(i('rePassword'));
        $v_code = i('vCode');
        if(empty(json_encode($username))) {
            ajax(0, trans('admin.signIn.enterAcc'));
        }
        if(similar_text($username, "<,>/?~`!@#%^&*()+|\='{}.。，「」（）《》") > 0) {
            ajax(0, trans('admin.signIn.uSpe'));
        }
        if(strlen($username) <= 3) {
            ajax(0, trans('admin.signIn.uLen'));
        }
        if(empty(json_encode($password))) {
            ajax(0, trans('admin.signIn.enterPwd'));
        }
        if($password !== $re_password) {
            ajax(0, trans('admin.signUp.enterMatchPwd'));
        }
        if(empty($v_code)) {
            ajax(0, trans('admin.signIn.enterVCode'));
        }
        if($v_code != session('verify_code')) {
            ajax(0, trans('admin.signIn.vCodeErr'));
        }

        $check = m($this->table)
            ->where([
                'name' => $username,
            ])
            ->find();
        if($check) {
            ajax(0, trans('admin.signUp.accAlready'));
        }

        $re = m($this->table)
            ->insert([
                'name' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'roles' => '4',
                'status' => 1,
                'ctime' => time(),
                'utime' => time(),
            ]);
        if($re) {
            ajax(1, trans('admin.signUp.success'));
        }else {
            ajax(0, trans('admin.signUp.error'));
        }
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
        ajax(1, trans('admin.signIn.signOutOk'), $url);
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
            ajax(0, trans('admin.signIn.accNoFound'));
        }
        if(!$user_info['roles']) {
            ajax(0, trans('admin.signIn.noRoles'));
        }

        //获取用户角色
        $role_info = m('sys_roles')
            ->where('id in (' . $user_info['roles'] . ')')
            ->where([
                'status' => 1,
            ])
            ->select();
        if(!is_array($role_info) || empty($role_info)) {
            ajax(0, trans('admin.signIn.noFoundRoles'));
        }

        //获取菜单权限
        $menu_ids = [];
        foreach ($role_info as $key => $item) {
            $item['menu_ids'] = explode(',', $item['menu_ids']);
            $menu_ids = array_merge($menu_ids, $item['menu_ids']);
        }
        if(!is_array($menu_ids) || empty($menu_ids)) {
            ajax(0, trans('admin.signIn.roleNotConfigMenu'));
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
            ajax(0, trans('admin.signIn.menuInvalid'));
        }

        //组装访问白名单
        $menu_path = array_column($menu_info, 'href');
        $menu_path = array_filter($menu_path);
        $menu_path = array_unique($menu_path);
        $menu_path = array_merge($menu_path, [
            '/dash',
            '/dash/userInfo',
            '/menu/load',
        ]);

        //检查用户权限
        $url_path = $_SERVER['PATH_INFO'];
        $url_path = str_replace('//', '/', $url_path);
        if(!$url_path) {
            ajax(0, trans('admin.signIn.requestInvalid'));
        }

        $url_path = str_replace('/admin', '', $url_path);
        if(!in_array($url_path, $menu_path)) {
            ajax(0, trans('admin.signIn.AccessPermissions'));
        }
    }

    /**
     * login __deconstruct
     */
    public function __deconstruct() {}
}
