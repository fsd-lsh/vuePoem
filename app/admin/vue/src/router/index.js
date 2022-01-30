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
            component: resolve => require(['@/views/admin/signIn'], resolve),
            meta: {
                title: '登录' + title,
            },
        },

        {
            path: '/userInfo',
            name: 'system',
            component: resolve => require(['@/views/admin/userInfo'], resolve),
            meta: {
                title: '用户信息 - 系统管理' + title,
            },
        },

        {
            path: '/dash',
            name: 'dash',
            component: resolve => require(['@/views/admin/dash'], resolve),
            meta: {
                title: '面板统计 - 业务管理' + title,
            },
        },

        {
            path: '/cms',
            name: 'cms',
            component: resolve => require(['@/views/admin/cms'], resolve),
            meta: {
                title: 'cms - 业务管理' + title,
            },
        },

        {
            path: '/menu',
            name: 'menu',
            component: resolve => require(['@/views/admin/menu'], resolve),
            meta: {
                title: '菜单设置 - 系统管理' + title,
            },
        },

        {
            path: '/roles',
            name: 'roles',
            component: resolve => require(['@/views/admin/roles'], resolve),
            meta: {
                title: '角色设置 - 系统管理' + title,
            },
        },

        {
            path: '/user',
            name: 'user',
            component: resolve => require(['@/views/admin/user'], resolve),
            meta: {
                title: '用户管理 - 系统管理' + title,
            },
        },

        {
            path: '/log/fw_log',
            name: 'fw_log',
            component: resolve => require(['@/views/admin/fwLog'], resolve),
            meta: {
                title: '框架日志 - 系统管理' + title,
            },
        },

        {
            path: '/log/sys_log',
            name: 'sys_log',
            component: resolve => require(['@/views/admin/sysLog'], resolve),
            meta: {
                title: '运行日志 - 系统管理' + title,
            },
        },

        {
            path: '/system',
            name: 'sys',
            component: resolve => require(['@/views/admin/system'], resolve),
            meta: {
                title: '系统监控 - 系统管理' + title,
            },
        },
    ],
})
