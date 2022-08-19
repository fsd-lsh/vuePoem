<?php

use \service\middleware\restful;

if(!function_exists('sys_api')) {

    /**
     * Func: sys_api
     * User: Force
     * Date: 2021/4/4
     * Time: 15:33
     * Desc: RPC接口助手
     */
    function sys_api($table = NULL) {

        //API模式运行
        if(!empty(i('api'))) {

            //接收请求
            $api = i('api');

            //检测API表
            if(empty($table) && !is_array($table)) {
                ajax(0, '控制器没有开发API');
            }

            //检测API
            $table_keys = array_keys($table);
            if(!in_array($api, $table_keys)) {
                ajax(0, '控制器找不到您请求的API');
            }

            //执行
            $table[$api]();
            exit;
        }else {

            //def
            echo 'API running.....';
            exit;
        }
    }
}

if(!function_exists('restful')) {

    /**
     * Func: restful
     * User: Force
     * Date: 2022/5/5
     * Time: 18:24
     * Desc: restful接口助手
     */
    function restful($table = NULL) {

        $restful = new restful();
        $allow_method = config('restful_method');
        $request_method = strtolower($_SERVER['REQUEST_METHOD']);

        if(empty($table) && !is_array($table)) {
            $restful->response(0, trans('admin.restful.noSupportMethod'));
        }

        foreach ($table as $method => $func) {

            if(!in_array($method, $allow_method)) {
                $restful->response(0, trans('admin.restful.noSupportMethod').':'.$method);
            }
        }

        if(empty($table[$request_method])) {
            $restful->response(0, trans('admin.restful.methodNotFound'));
        }

        $table[$request_method]($restful);
        exit;
    }
}

if(!function_exists('array_group_by')) {

    /**
     * Func: array_group_by
     * User: Force
     * Date: 2020/7/31
     * Time: 10:04
     * Desc: 多维数组GROUP BY
     */
    function array_group_by($arr, $key) {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
    }
}

if(!function_exists('array_order_by')) {

    /**
     * Func: array_order_by
     * User: Force
     * Date: 2020/7/25
     * Time: 13:57
     * Desc: PHP多维数组ORDER BY
     */
    function array_order_by(){
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}

if(!function_exists('res_safe')) {

    /**
     * Func: res_safe
     * User: Force
     * Date: 2021/4/4
     * Time: 17:46
     * Desc: 请求过滤
     */
    function res_safe($data, $ignore_magic_quotes = false) {

        if(is_string($data)) {

            //防止被挂马，跨站攻击
            $data = trim(htmlspecialchars($data));
            if($ignore_magic_quotes) {

                //防止sql注入
                $data = addslashes($data);
            }
            return  $data;

        }else if(is_array($data)) {

            //如果是数组采用递归过滤
            foreach($data as $key => $value) {
                $data[$key] = res_safe($value);
            }
            return $data;

        }else {
            return $data;
        }
    }
}

if(!function_exists('func_comment')) {

    /**
     * Func: func_comment
     * User: Force
     * Date: 2021/9/25
     * Time: 15:50
     * Desc: 获取方法注释
     */
    function func_comment($module, $fc, $line_name) {

        if(empty($module) || empty($fc) || empty($line_name)) {
            return false;
        }

        $act = new $module();
        $func = new ReflectionMethod($act, $fc);
        $tmp = $func->getDocComment();

        preg_match_all('/' . $line_name . ':(.*?)\n/',$tmp,$tmp);

        $tmp = trim($tmp[1][0]);
        $tmp = $tmp != '' ? $tmp : 'null';

        return $tmp;
    }
}

if(!function_exists('fetch_lang')) {

    /**
     * Func: fetch_lang
     * User: Force
     * Date: 2022/5/13
     * Time: 19:50
     * Desc: 获取语言
     */
    function trans($str = NULL) {

        if(!$_GET['lang']) {
            $_GET['lang'] = 'en';
        }

        if(!empty(SYS_LANG) && is_array(SYS_LANG)) {

            if(strpos($str, '.')) {
                $keys = explode('.', $str);
                switch (count($keys)) {
                    case 2: { return SYS_LANG[$keys[0]][$keys[1]]; }
                    case 3: { return SYS_LANG[$keys[0]][$keys[1]][$keys[2]]; }
                }
            }else {
                return SYS_LANG;
            }
        }

        $path = config('vue_project').config('lang_path');
        $path = realpath($path);
        $lang_json = file_get_contents($path . '/' . $_GET['lang'] . '.json');

        if(empty($lang_json) && !IS_CLI) {
            ajax(0, 'language not support');
        }

        $lang_json = json_decode($lang_json, true);

        return $lang_json;
    }
}

if(!function_exists('lang_edit')) {

    /**
     * Func: lang_edit
     * User: Force
     * Date: 2022/8/15
     * Time: 17:11
     * Desc: 语言包编辑
     */
    function lang_edit($mode = NULL, $id = NULL, $menu_name = []) {

        if($mode != 'add' && $mode != 'del' && empty($id)) {
            return false;
        }
        if($mode == 'add') {
            if(empty($menu_name)) {
                return false;
            }
        }
        if(in_array($id, [1,2,3,4,5,6,7,8,9,11])) { //排除系统预设菜单
            return false;
        }

        $lang_path = config('vue_project').config('lang_path');
        $file_list = scandir($lang_path);
        unset(
            $file_list[array_search('.', $file_list)],
            $file_list[array_search('..', $file_list)],
        );
        if(!is_array($file_list) || empty($file_list)) {
            return false;
        }

        $lang = array_keys($menu_name);

        foreach ($file_list as $item) {

            $item = str_replace('.json', '', $item);

            if(!in_array($item, $lang)) {
                continue;
            }

            $menu = file_get_contents($lang_path.'/'.$item.'.json');
            $menu = json_decode($menu, true);

            switch ($mode) {
                case 'add': {
                    if($menu_name[$item]) {
                        $menu['admin']['userMenu']['menu_id_'.$id] = $menu_name[$item];
                    }
                    break;
                }
                case 'del': {
                    unset($menu['admin']['userMenu']['menu_id_'.$id]);
                    break;
                }
            }

            $menu = json_encode($menu, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
            file_put_contents($lang_path.'/'.$item.'.json', $menu);
        }

        return true;
    }
}
