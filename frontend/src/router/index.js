import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

const title = ' - adminPoem2';

export default new Router({

    mode:'hash',

    routes: [

        {
            path: '/',
            name: 'Sign In',
            component: resolve => require(['@/views/signIn'], resolve),
            meta: {
                title: '登录' + title,
            },
        },

        {
            path: '/dash',
            name: 'dash',
            component: resolve => require(['@/views/dash'], resolve),
            meta: {
                title: '面板统计 - 业务管理' + title,
            },
        },

        {
            path: '/menu',
            name: 'menu',
            component: resolve => require(['@/views/menu'], resolve),
            meta: {
                title: '菜单设置 - 系统管理' + title,
            },
        },

        {
            path: '/roles',
            name: 'roles',
            component: resolve => require(['@/views/roles'], resolve),
            meta: {
                title: '角色设置 - 系统管理' + title,
            },
        },

        {
            path: '/user',
            name: 'user',
            component: resolve => require(['@/views/user'], resolve),
            meta: {
                title: '用户管理 - 系统管理' + title,
            },
        },

        {
            path: '/log',
            name: 'log',
            component: resolve => require(['@/views/fwLog'], resolve),
            redirect: '/log/fw_log',  //默认子路由加载首页
            children: [

                {
                    path: '/log/fw_log',
                    name: 'fw_log',
                    component: resolve => require(['@/views/fwLog'], resolve),
                    meta: {
                        title: '框架日志 - 系统管理' + title,
                    },
                },

                {
                    path: '/log/sys_log',
                    name: 'sys_log',
                    component: resolve => require(['@/views/sysLog'], resolve),
                    meta: {
                        title: '运行日志 - 系统管理' + title,
                    },
                },
            ],
        },

        {
            path: '/system',
            name: 'system',
            component: resolve => require(['@/views/system'], resolve),
            meta: {
                title: '系统监控 - 系统管理' + title,
            },
        },
    ],
})
