<?php

namespace admin\controller;

use service\middleware;

/**
 * Class menu
 * @package admin\controller
 */
class menu extends middleware\login {

    /**
     * menu constructor.
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct(1, 'admin');
    }

    /**
     * Func: index
     * User: Force
     * Date: 2021/4/4, Update:2022/8/11
     * Time: 20:30
     * Desc: 菜单设置
     */
    public function index() {

        //API
        sys_api([

            //添加菜单
            'add' => function() {

                $data = [];
                if(i('title')) {
                    $data['title'] = i('title');
                }
                if(is_numeric(intval(i('pid')))) {
                    $data['pid'] = intval(i('pid'));
                }
                if(i('icon')) {
                    $data['icon'] = i('icon');
                }
                if(i('href')) {
                    $data['href'] = i('href');
                }
                if(i('target')) {
                    $data['target'] = i('target');
                }
                if(is_numeric(intval(i('sort')))) {
                    $data['sort'] = intval(i('sort'));
                }
                if(is_numeric(intval(i('show')))) {
                    $data['show'] = intval(i('show'));
                }

                if(empty($data['title'])) { ajax(0, 'title '.trans('admin.public.isEmpty')); }
                if(empty($data['target'])) { ajax(0, 'target '.trans('admin.public.isEmpty')); }
                if(!is_numeric($data['sort'])) { ajax(0, 'sort '.trans('admin.public.notNumber')); }
                if(empty($data)) { ajax(0, trans('admin.public.checkForm')); }

                if(!is_numeric($data['pid'])) {
                    $data['pid'] = 0;
                }

                $data['ctime'] = time();
                $data['utime'] = time();

                $menu_id = m('sys_menu')
                    ->insert($data);

                if($menu_id) {

                    lang_edit('add', $menu_id, [$_GET['lang'] => $data['title']]);

                    ajax(1, trans('admin.public.success'));
                }else {
                    ajax(0, trans('admin.public.error'));
                }
            },

            //变更状态
            'status_change' => function() {

                $ids = $_POST['ids'];
                $status = intval(i('status'));

                if(empty($ids) || !is_array($ids)) { ajax(0, trans('admin.public.checkRec')); }
                if(!isset($status)) { ajax(0, trans('admin.public.statusErr')); }

                switch ($status) {
                    case 2: { $info = trans('admin.menu.stop'); break; }
                    case 1: { $info = trans('admin.menu.open'); break; }
                    case 0: { $info = trans('admin.menu.del'); break; }
                    default: { ajax(0, trans('admin.public.statusErr')); break; }
                }

                $ids = implode(',', $ids);
                $data = [
                    'status' => $status,
                    'utime' => time(),
                ];

                if($status == 0) {
                    $data['dtime'] = time();
                }

                $result = m('sys_menu')
                    ->where("id in ({$ids})")
                    ->update($data);

                if($result) {
                    ajax(1, $info.trans('admin.public.success'));
                }else {
                    ajax(0, $info.trans('admin.public.error'));
                }
            },

            //保存菜单
            'save' => function() {

                $id = intval(i('id'));
                $data = [];
                $data['icon'] = i('icon');
                if(i('title')) {
                    $data['title'] = i('title');
                }
                if(is_numeric(intval(i('pid')))) {
                    $data['pid'] = intval(i('pid'));
                }
                if(i('href')) {
                    $data['href'] = i('href');
                }
                if(i('target')) {
                    $data['target'] = i('target');
                }
                if(is_numeric(intval(i('sort')))) {
                    $data['sort'] = intval(i('sort'));
                }
                if(is_numeric(intval(i('show')))) {
                    $data['show'] = intval(i('show'));
                }

                if(empty($id)) { ajax(0, 'ID '.trans('admin.public.isEmpty')); }
                if(empty($data['title'])) { ajax(0, 'title '.trans('admin.public.isEmpty')); }
                if(!is_numeric($data['pid'])) { ajax(0, 'pid '.trans('admin.public.notNumber')); }
                if(empty($data['href']) && $data['pid'] != 0) { ajax(0, 'href '.trans('admin.public.isEmpty')); }
                if(!is_numeric($data['sort'])) { ajax(0, 'sort '.trans('admin.public.notNumber')); }
                if(empty($data)) { ajax(0, trans('admin.public.checkForm')); }
                $data['utime'] = time();

                $result = m('sys_menu')
                    ->where([
                        'id' => $id,
                    ])
                    ->update($data);

                if($result) {

                    lang_edit('add', $id, [$_GET['lang'] => $data['title']]);

                    ajax(1, trans('admin.public.success'));
                }else {
                    ajax(0, trans('admin.public.error'));
                }
            },

            //加载菜单
            'load' => function () {

                $id = gp('id');

                $result = m('sys_menu')
                    ->where([
                        'id' => $id,
                    ])
                    ->find();

                if($result) {
                    $result['title'] = trans('admin.userMenu.menu_id_'.$result['id']);
                    ajax(1, trans('admin.public.success'), $result);
                }else {
                    ajax(0, trans('admin.public.error'));
                }
            },

            //列表
            'lists' => function() {

                //数据格式化
                $menu_config = [];
                function formatting(&$data, &$menu_config, $lang_config) {

                    if(empty($data) || !is_array($data)) {
                        return false;
                    }

                    $temp = [];
                    foreach ($data as $key => $item) {

                        $now_data = [
                            'id' => $item['id'],
                            'pid' => $item['pid'],
                            'title' => $lang_config['menu_id_'.$item['id']],
                            'icon' => $item['icon'],
                            'href' => $item['href'],
                            'target' => $item['target'],
                            'sort' => $item['sort'],
                            'status' => $item['status'],
                            'status_mean' => ($item['status'] == 1) ? trans('admin.menu.open') : trans('admin.menu.stop'),
                            'lock' => ($item['lock'] == 1) ? trans('admin.menu.locked') : trans('admin.menu.unlock'),
                            'show' => ($item['show'] == 1) ? trans('admin.menu.show') : trans('admin.menu.hide'),
                            'remark' => $item['remark'],
                            'ctime' => $item['ctime'] ? date('Y-m-d H:i:s', $item['ctime']) : '',
                            'utime' => $item['utime'] ? date('Y-m-d H:i:s', $item['utime']) : '',
                            'dtime' => $item['dtime'] ? date('Y-m-d H:i:s', $item['dtime']) : '',
                        ];

                        $temp[] = $now_data;

                        array_push($menu_config, $now_data);
                    }
                    $data = $temp;
                }

                //加载菜单
                $menu = m('sys_menu')
                    ->field('*')
                    ->where([
                        'pid' => 0,
                    ])
                    ->where('status != 0')
                    ->select();
                formatting($menu, $menu_config, trans('admin.userMenu'));
                if(is_array($menu) && !empty($menu)) {

                    $pids = array_column($menu, 'id');
                    $sub_menu = m()
                        ->query("select * from ".config('db_prefix')."sys_menu where pid in (" . implode(',', $pids) . ") and status != 0");
                    formatting($sub_menu, $menu_config, trans('admin.userMenu'));
                    $sub_pids = array_column($sub_menu, 'id');
                    $sub_menu = array_group_by($sub_menu, 'pid');
                    $sub_menu2 = m()
                        ->query("select * from ".config('db_prefix')."sys_menu where pid in (" . implode(',', $sub_pids) . ") and status != 0");
                    formatting($sub_menu2, $menu_config, trans('admin.userMenu'));
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

                //加载字体图标
                $font_map = file_get_contents(config('class_font_map_path'));
                $font_map = json_decode($font_map, true);

                //构建后台菜单链接
                $link_config = $this->build_menu_link();

                //响应
                ajax(1, trans('admin.public.success'), [
                    'lists' => $menu,
                    'menu_config' => $menu_config,
                    'font_map' => $font_map,
                    'link_config' => $link_config,
                ]);
            },
        ]);
    }

    /**
     * Func: load
     * User: Force
     * Date: 2022/1/15
     * Time: 17:00
     * Desc: 加载菜单
     */
    public function load() {

        $menu = [
            'homeInfo' => [
                'title' => trans('admin.userMenu.menu_id_2'),
                'href'  => '/menu',
            ],
            'logoInfo' => [
                'title' => 'VuePoem',
                //'image' => '',
            ],
            'menuInfo' => $this->get_menu_list(),
        ];

        if(in_array($_GET['lang'], ['zh', 'en'])) {
            $_SESSION['admin_info']['lang'] = $_GET['lang'];
        }

        ajax(1, trans('admin.public.success'), $menu);
    }

    /**
     * Func: get_menu_list
     * User: Force
     * Date: 2021/4/4
     * Time: 19:05
     * Desc: 获取菜单列表
     */
    private function get_menu_list() {

        //获取菜单
        $menuList = m('sys_menu')
            ->field('id,pid,title,icon,href,target')
            ->where([
                'status' => 1,
                'show' => 1,
            ])
            ->order('sort', 'desc')
            ->select();

        //语言处理
        foreach ($menuList as $key => $item) {
            $menuList[$key]['title'] = trans('admin.userMenu.menu_id_'.$item['id']);
        }

        //权限分配
        if(is_array($menuList) && !empty($menuList)) {
            $menu_ids = [];
            if($_SESSION['admin_info']['roles']) {

                $role_info = m('sys_roles')
                    ->where('id in ('.$_SESSION['admin_info']['roles'].')')
                    ->select();

                if(is_array($role_info) && !empty($role_info)) {

                    foreach ($role_info as $key => $item) {

                        if($item['menu_ids']) {
                            $item['menu_ids'] = explode(',', $item['menu_ids']);
                            $menu_ids = array_merge($menu_ids, $item['menu_ids']);
                        }
                    }
                }
            }
            $menu_ids = array_filter($menu_ids);
            $menu_ids = array_unique($menu_ids);

            foreach ($menuList as $key => $item) {
                if(!in_array($item['id'], $menu_ids)) {
                    unset($menuList[$key]);
                }
            }
        }

        //构建子菜单
        $menuList = $this->build_menu_child(0, $menuList);

        //反馈
        return $menuList;
    }

    /**
     * Func: buildMenuChild
     * User: Force
     * Date: 2021/4/4
     * Time: 19:05
     * Desc: 递归获取子菜单
     */
    private function build_menu_child($pid, $menuList) {

        $treeList = [];

        foreach ($menuList as $v) {

            if ($pid == $v['pid']) {

                $node = $v;
                $child = $this->build_menu_child($v['id'], $menuList);

                if (!empty($child)) {
                    $node['child'] = $child;
                }else {
                    $node['child'] = [];
                }
                $node['rand_name'] = time() . '-' . rand(1234, 9999);
                $node['name'] = explode('/', $node['href']);
                $node['name'] = $node['name'][count($node['name']) - 1];

                $treeList[] = $node;
            }
        }
        return $treeList;
    }

    /**
     * Func: build_menu_link
     * User: Force
     * Date: 2021/9/25
     * Time: 11:46
     * Desc: 构建后台菜单链接
     */
    private function build_menu_link() {

        //控制器path
        $admin_path = realpath(__DIR__) . '/';

        //扫描控制器
        $controllers = scandir($admin_path);
        unset(
            $controllers[array_search('.', $controllers)],
            $controllers[array_search('..', $controllers)],
            $controllers[array_search('index.php', $controllers)],
            $controllers[array_search('dash.php', $controllers)]
        );

        //反射methods
        if(!is_array($controllers) || empty($controllers)) {
            return false;
        }
        $methods = [];
        foreach ($controllers as $controller) {

            //反射
            $controller = str_replace('.php', '', $controller);
            $reflection = new \ReflectionClass("admin\\controller\\{$controller}");
            $method = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            $method = json_decode(json_encode($method),true);

            //组装
            if(is_array($method) && !empty($method)) {

                //白名单
                $filter = ['__construct', '__deconstruct', 'check_login', 'sign_in', 'sign_up', 'sign_out', 'log'];

                //格式化
                foreach ($method as $key => $func) {

                    //排除
                    if(in_array($func['name'], $filter)) {
                        unset($method[$key]);
                        continue;
                    }
                    if($controller == 'menu' && $func['name'] == 'load') {
                        unset($method[$key]);
                        continue;
                    }

                    //link
                    if($func['name'] == 'index') {
                        $link = '/' . $controller;
                    }else {
                        $link = '/' . $controller . '/' . $func['name'];
                    }

                    //comment
                    if($_SESSION['admin_info']['lang'] == 'zh') {
                        $class = "\\admin\\controller\\{$controller}";
                        $comment = func_comment($class, $func['name'], 'Desc');
                    }else {
                        $comment = '';
                    }

                    //save
                    $method[$key] = [
                        'link' => $link,
                        'comment' => $comment,
                    ];

                }
                $methods[$controller] = $method;
            }else {
                $methods[$controller] = [];
            }
            unset($method);
        }

        return is_array($methods) ? $methods : [];
    }
}
