<?php

namespace admin\controller;

use service\middleware;

/**
 * Class user
 * @package admin\controller
 */
class user extends middleware\login {

    /**
     * user constructor.
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct(1, 'admin');
    }

    /**
     * Func: index
     * User: Force
     * Date: 2021/4/7, Update:2022/8/11
     * Time: 20:42
     * Desc: 用户管理
     */
    public function index() {

        //API
        sys_api([

            //创建用户
            'create_user' => function() {

                if(gp('password') != gp('rePassword')) {
                    ajax(0, trans('admin.user.pwdErr'));
                }

                $check = m('sys_admin')
                    ->where([
                        'name' => gp('name'),
                        'status' => 1,
                    ])
                    ->find();
                if($check) {
                    ajax(0, trans('admin.user.accAlr'));
                }

                $result = m('sys_admin')
                    ->insert([
                        'name' => gp('name'),
                        'password' => password_hash(gp('password'), PASSWORD_DEFAULT),
                        'roles' => implode(',', $_REQUEST['roles']),
                        'status' => 1,
                        'ctime' => time(),
                        'utime' => time(),
                    ]);

                if($result) {
                    ajax(1, trans('admin.user.createOk'));
                }else {
                    ajax(0, trans('admin.user.createErr'));
                }
            },

            //用户状态变更
            'status_change' => function() {

                $status = gp('status');

                $ids = $_POST['ids'];
                if(empty($ids)) {
                    ajax(0, 'ids,' . trans('admin.user.notNull'));
                }

                $ids = array_filter($ids);
                $ids = implode(',', $ids);
                if(empty($ids)) {
                    ajax(0, trans('admin.user.noRec'));
                }

                $result = m('sys_admin')
                    ->where("id in ($ids)")
                    ->update([
                        'status' => intval($status),
                        'utime' => time(),
                    ]);

                if($result) {
                    ajax(1, trans('admin.user.changeOk'));
                }else {
                    ajax(0, trans('admin.user.changeErr'));
                }
            },

            //用户信息变更
            'info_change' => function() {

                $form = $_REQUEST['form'];
                if(empty($form) || !is_array($form)) {
                    ajax(0, 'form,' . trans('admin.user.notNull'));
                }
                if(empty($form['name'])) {
                    ajax(0, 'username,' . trans('admin.user.notNull'));
                }
                if(empty($form['roles']) || !is_array($form['roles'])) {
                    ajax(0, trans('admin.user.selRoles'));
                }

                $data = [
                    'name' => $form['name'],
                    'roles' => implode(',', $form['roles']),
                    'utime' => time(),
                ];
                if($form['password']) {
                    $data['password'] = password_hash($form['password'], PASSWORD_DEFAULT);
                }

                $result = m('sys_admin')
                    ->where([
                        'id' => intval($form['id'])
                    ])
                    ->update($data);

                if($result) {
                    ajax(1, trans('admin.user.modUserOk'));
                }else {
                    ajax(0, trans('admin.user.modUserErr'));
                }
            },

            //加载列表
            'load' => function() {

                //加载用户列表
                $lists = m('sys_admin')
                    ->field('id, name, password, roles, status, ctime, utime')
                    ->where('status != 0')
                    ->order('id asc');
                $page_info = \poem\more\page::run($lists, '/#/user', 15);

                //获取角色信息
                $roles_info = m('sys_roles')
                    ->select();
                if(is_array($roles_info) && !empty($roles_info)) {
                    $roles_info = array_combine(array_column($roles_info, 'id'), $roles_info);
                }

                //数据格式化
                $lists = $page_info['list'];
                if(is_array($lists) && !empty($lists)) {

                    foreach ($lists as $key => $item) {

                        //角色
                        $item['roles'] = explode(',', $item['roles']);
                        if(is_array($item['roles']) && !empty($item['roles'])) {
                            $temp = [];
                            foreach ($item['roles'] as $s_key => $s_item) {
                                if($roles_info[$s_item]['name']) {
                                    $temp[] = $roles_info[$s_item]['name'];
                                }
                            }
                            $lists[$key]['roles_mean'] = $temp;
                            unset($temp);
                        }
                        $lists[$key]['roles'] = $item['roles'];

                        //状态含义
                        $lists[$key]['status_mean'] = trans('admin.user.status');

                        //创建时间
                        $lists[$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);

                        //更新时间
                        $lists[$key]['utime'] = date('Y-m-d H:i:s', $item['utime']);
                    }
                }

                //渲染视图
                ajax(1, trans('admin.user.loadOk'), [
                    'lists' => $lists,
                    'page_html' => $page_info['html'],
                    'roles_config' => $roles_info,
                ]);
            },
        ]);
    }
}
