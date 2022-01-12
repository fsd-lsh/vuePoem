import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({

    mode:'hash',

    routes: [

        {
            path: '/',
            name: 'Sign In',
            component: resolve => require(['@/views/signIn'], resolve),
            meta: {
                title: '登录 - adminPoem2',
            },
        },

        {
            path: '/dash',
            name: 'Sign In',
            component: resolve => require(['@/views/dash'], resolve),
            meta: {
                title: '面板统计 - 业务管理 - adminPoem2后台',
            },
        },
    ],
})
