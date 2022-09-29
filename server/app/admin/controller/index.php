<?php

namespace admin\controller;

use service\middleware;

class index extends middleware\login {

    /**
     * Func: index
     * User: Force
     * Date: 11/28/20
     * Time: 8:45 PM
     * Desc: 后台首页
     */
    public function index() {

        //API
        sys_api([

            //登录
            'sign_in' => function() {
                parent::__construct(0, 'admin');
                parent::sign_in();
            },

            //注销
            'sign_out' => function() {
                parent::__construct(0, 'admin');
                parent::sign_out();
            },

            //语言切换
            'lang' => function() {

                $lang = $_REQUEST['lang'];
                $lang_json = config('lang_path').'/'.$lang.'.json';

                if(empty($lang) || file_exists($lang_json)) {
                    $user_info = is_array(session('admin_info')) && !empty(session('admin_info'))
                        ? session('admin_info') : [];
                    session('admin_info', array_merge($user_info, [
                        'lang' => $lang
                    ]));
                    ajax(1, 'The language has been set successfully');
                }else {
                    ajax(0, "Language '{$lang}' is not supported");
                }
            },
        ]);
    }

    /**
     * Func: theme
     * User: Force
     * Date: 2022/8/19
     * Time: 16:27
     * Desc: 主题
     */
    public function theme() {

        sys_api([

            //加载主题
            'load' => function() {

                $theme_css_file = file_get_contents(realpath(APP_PATH . '../../' .config('theme_path')).'/theme.less');
                if(empty($theme_css_file)) {
                    ajax(0, trans('admin.public.error'));
                }

                preg_match_all('/.sys-theme-(.*)/', $theme_css_file, $theme_css_file);
                $theme_css_file = $theme_css_file[0];
                if(!is_array($theme_css_file)) {
                    ajax(0, trans('admin.public.error'));
                }

                $theme_css_file = array_map(function ($v) {
                    return str_replace([' ', '{', '.'], '', $v);
                }, $theme_css_file);
                ajax(1, trans('admin.public.success'), $theme_css_file);
            },
        ]);
    }
}
