<?php

namespace admin\controller;

use service\component;

/**
 * Class roles
 * @package admin\controller
 */
class roles extends component\login {

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
     * Date: 2021/4/7
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
                    ajax(0, '请输入角色名称');
                }
                if(empty($form['menu_ids']) || !is_array($form['menu_ids'])) {
                    ajax(0, '请选择角色菜单');
                }

                $check = m('sys_roles')
                    ->where([
                        'name' => $form['name'],
                        'status' => 1,
                    ])
                    ->find();
                if($check) {
                    ajax(0, '同名角色已经存在');
                }

                $menu_ids = m()
                    ->query('
                        select * from poem_sys_menu where status = 1 and id in('.implode(',', $form['menu_ids']).')
                    ');
                if(!$menu_ids) {
                    ajax(0, '菜单不存在或已被删除 B2');
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
                    ajax(1, '创建角色完成');
                }else {
                    ajax(0, '创建角色失败');
                }
            },

            //批量修改状态
            'status_change' => function() {

                $status = i('status');
                $ids = $_POST['ids'];
                if(!is_array($ids) || empty($ids)) {
                    ajax(0, '请先勾选记录');
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

                ajax(1, "成功变更状态{$success}个, 失败{$error}个。");
            },

            //更新角色信息
            'edit_role' => function() {

                $form = $_POST['form'];
                if(!$form['name']) {
                    ajax(0, '请输入角色名称');
                }
                if(empty($form['menu_ids']) || !is_array($form['menu_ids'])) {
                    ajax(0, '请选择角色菜单');
                }

                $menu_ids = m()
                    ->query('
                        select * from poem_sys_menu where status = 1 and id in('.implode(',', $form['menu_ids']).')
                    ');
                if(!$menu_ids) {
                    ajax(0, '菜单不存在或已被删除 B2');
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
                    ajax(1, '修改角色完成');
                }else {
                    ajax(0, '修改角色失败');
                }
            },
        ]);

        //获取角色数据
        $roles_lists = m('sys_roles')
            ->where([
                'status' => ['!=', 0],
            ])
            ->order('id asc');
        $roles_lists = \poem\more\page::run($roles_lists, '/admin/roles', 15);
        $lists = $roles_lists['list'];
        $page_html = $roles_lists['html'];

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
        $data = [];
        foreach ($lists as $key => $item) {

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

            //组装数据
            $data[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'menu_ids' => $menu_ids,
                'status' => $item['status'],
                'status_mean' => config('role_status')[$item['status']],
                'ctime' => date('Y-m-d H:i:s', $item['ctime']),
                'utime' => date('Y-m-d H:i:s', $item['utime']),
            ];
        }

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

        //渲染视图
        assign([
            'lists' => json_encode($data),
            'page_html' => $page_html,
            'menu_config' => json_encode($menu),
        ]);
        vue();
    }
}
