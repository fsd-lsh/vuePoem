<?php

namespace admin\controller;

use service\middleware;

/**
 * Class roles
 * @package admin\controller
 */
class roles extends middleware\login {

    /**
     * roles constructor.
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct(1, 'admin');
    }

    /**
     * Func: index
     * User: Force
     * Date: 2021/4/7, Update:2022/8/11
     * Time: 20:47
     * Desc: 角色设置
     */
    public function index() {

        //API
        sys_api([

            //添加角色
            'add_role' => function() {

                $form = $_POST['form'];
                if(!$form['name']) {
                    ajax(0, trans('admin.roles.enterName'));
                }
                if(empty($form['menu_ids']) || !is_array($form['menu_ids'])) {
                    ajax(0, trans('admin.roles.selectMenu'));
                }

                $check = m('sys_roles')
                    ->where([
                        'name' => $form['name'],
                        'status' => 1,
                    ])
                    ->find();
                if($check) {
                    ajax(0, trans('admin.roles.nameExists'));
                }

                $menu_ids = m()
                    ->query('
                        select * from poem_sys_menu where status = 1 and id in('.implode(',', $form['menu_ids']).')
                    ');
                if(!$menu_ids) {
                    ajax(0, trans('admin.roles.nameExists') . ' B2');
                }

                $pids = array_column($menu_ids, 'pid');
                $menu_ids = array_column($menu_ids, 'id');
                $menu_ids = array_merge($menu_ids, $pids);
                $menu_ids = array_filter($menu_ids);
                $menu_ids = array_unique($menu_ids);
                $menu_ids = implode(',', $menu_ids);

                $result = m('sys_roles')
                    ->insert([
                        'name' => $form['name'],
                        'menu_ids' => $menu_ids,
                        'status' => 1,
                        'ctime' => time(),
                        'utime' => time(),
                    ]);

                if($result) {
                    ajax(1, trans('admin.roles.createOk'));
                }else {
                    ajax(0, trans('admin.roles.createErr'));
                }
            },

            //批量修改状态
            'status_change' => function() {

                $status = i('status');
                $ids = $_POST['ids'];
                if(!is_array($ids) || empty($ids)) {
                    ajax(0, trans('admin.roles.checkRec'));
                }

                $success = 0;
                $error = 0;
                foreach ($ids as $id) {

                    $result = m('sys_roles')
                        ->where([
                            'id' => $id,
                        ])
                        ->update([
                            'status' => intval($status),
                        ]);
                    if($result) {
                        $success++;
                    }else {
                        $error++;
                    }
                }

                ajax(1, trans('admin.roles.changeOk') . ":{$success}, " . trans('admin.roles.changeErr') . ":{$error}");
            },

            //更新角色信息
            'edit_role' => function() {

                $form = $_POST['form'];
                if(!$form['name']) {
                    ajax(0, trans('admin.roles.enterName'));
                }
                if(empty($form['menu_ids']) || !is_array($form['menu_ids'])) {
                    ajax(0, trans('admin.roles.selectMenu'));
                }

                $menu_ids = m()
                    ->query('
                        select * from poem_sys_menu where status = 1 and id in('.implode(',', $form['menu_ids']).')
                    ');
                if(!$menu_ids) {
                    ajax(0, trans('admin.roles.nameExists') . ' B2');
                }

                $pids = array_column($menu_ids, 'pid');
                $menu_ids = array_column($menu_ids, 'id');
                $menu_ids = array_merge($menu_ids, $pids);
                $menu_ids = array_filter($menu_ids);
                $menu_ids = array_unique($menu_ids);
                $menu_ids = implode(',', $menu_ids);

                $result = m('sys_roles')
                    ->where([
                        'status' => 1,
                        'id' => intval($form['id']),
                    ])
                    ->update([
                        'name' => $form['name'],
                        'menu_ids' => $menu_ids,
                    ]);

                if($result) {
                    ajax(1, trans('admin.roles.updateOk'));
                }else {
                    ajax(0, trans('admin.roles.updateErr'));
                }
            },

            //加载列表
            'load' => function() {

                $lists = m('sys_roles');
                $_POST['pageSize'] = $_POST['pageSize'] ? intval($_POST['pageSize']) : 15;

                $where = [
                    'status' => ['!=', 0],
                ];
                if(is_numeric($_POST['status'])) {
                    $where['status'] = intval($_POST['status']);
                }
                if(!empty($_POST['id'])) {
                    $where['id'] = intval($_POST['id']);
                }
                if(!empty($_POST['name'])) {
                    $lists->where("name like '%{$_POST['name']}%'");
                }
                if(!empty($_POST['menu_ids'])) {
                    if(is_array($_POST['menu_ids'])) {
                        $lists->where(sql_fis($_POST['menu_ids'], 'menu_ids'));
                    }else {
                        $where['menu_ids'] = intval($_POST['menu_ids']);
                    }
                }
                if(!empty($_POST['ctime'])) {
                    $_POST['ctime'][0] = substr($_POST['ctime'][0], 0, 10);
                    $_POST['ctime'][1] = substr($_POST['ctime'][1], 0, 10);
                    $lists->where("ctime >= '{$_POST['ctime'][0]}' && ctime <= '{$_POST['ctime'][1]}'");
                }
                if(!empty($_POST['utime'])) {
                    $_POST['utime'][0] = substr($_POST['utime'][0], 0, 10);
                    $_POST['utime'][1] = substr($_POST['utime'][1], 0, 10);
                    $lists->where("utime >= '{$_POST['utime'][0]}' && utime <= '{$_POST['utime'][1]}'");
                }

                //获取角色数据
                $lists
                    ->where($where)
                    ->order('id asc');
                $lists = \poem\more\page::run($lists, '/#/roles', $_POST['pageSize']);
                $data = $lists['list'];
                $page_html = $lists['html'];

                //获取菜单
                $pids = m('sys_menu')
                    ->field('pid')
                    ->where([
                        'status' => 1,
                    ])
                    ->select();
                if(is_array($pids)) {
                    $pids = array_column($pids, 'pid');
                    $pids = array_unique($pids);
                }

                //数据格式化
                $lists = [];
                foreach ($data as $key => $item) {

                    //过滤父级菜单ID
                    $menu_ids = explode(',', $item['menu_ids']);
                    if(is_array($menu_ids) && !empty($menu_ids)) {
                        foreach ($menu_ids as $s_key => $menu_id) {
                            if(in_array($menu_id, $pids)) {
                                unset($menu_ids[$s_key]);
                            }
                        }
                        sort($menu_ids);
                    }

                    //status
                    switch (intval($item['status'])) {
                        case 0: { $item['status_mean'] = trans('admin.roles.del'); break; }
                        case 1: { $item['status_mean'] = trans('admin.roles.open'); break; }
                        case 2: { $item['status_mean'] = trans('admin.roles.stop'); break; }
                    }

                    //组装数据
                    $lists[] = [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'menu_ids' => $menu_ids,
                        'status' => $item['status'],
                        'status_mean' => $item['status_mean'],
                        'ctime' => date('Y-m-d H:i:s', $item['ctime']),
                        'utime' => date('Y-m-d H:i:s', $item['utime']),
                    ];
                }
                unset($data);

                //组装菜单配置
                $menu = m('sys_menu')
                    ->field('id, pid, title as label')
                    ->where([
                        'pid' => 0,
                    ])
                    ->select();
                if(is_array($menu) && !empty($menu)) {

                    $pids = array_column($menu, 'id');
                    $sub_menu = m()
                        ->query("select id, pid, title as label from poem_sys_menu where pid in (" . implode(',', $pids) . ") and status = 1");
                    $sub_pids = array_column($sub_menu, 'id');
                    $sub_menu = array_group_by($sub_menu, 'pid');
                    $sub_menu2 = m()
                        ->query("select id, pid, title as label from poem_sys_menu where pid in (" . implode(',', $sub_pids) . ") and status = 1");
                    $sub_menu2 = array_group_by($sub_menu2, 'pid');

                    foreach($menu as $key => $item) {
                        if($sub_menu[$item['id']]) {
                            $menu[$key]['children'] = $sub_menu[$item['id']];
                            foreach ($menu[$key]['children'] as $s_key => $s_item) {
                                if($sub_menu2[$s_item['id']]) {
                                    $menu[$key]['children'][$s_key]['children'] = $sub_menu2[$s_item['id']];
                                }
                            }
                        }
                    }
                }

                //响应
                ajax(1, trans('admin.roles.loadOk'), [
                    'lists' => $lists,
                    'page_html' => $page_html,
                    'menu_config' => $menu,
                ]);
            },
        ]);
    }
}
