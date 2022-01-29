<?php

if(!function_exists('sys_api')) {

    /**
     * Func: sys_api
     * User: Force
     * Date: 2021/4/4
     * Time: 15:33
     * Desc: 系统API辅助
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
        }
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
            if(($ignore_magic_quotes==true)||(!@get_magic_quotes_gpc())) {

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
        $tmp = $tmp != '' ? $tmp : '无';

        return $tmp;
    }
}
