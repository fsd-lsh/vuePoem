import Vue from 'vue';
import Router from 'vue-router';
import axios from 'axios';

Vue.use(Router);

const title = ' - VuePoem';

let router = new Router({

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
            name: 'userInfo',
            component: resolve => require(['@/views/admin/userInfo'], resolve),
            meta: {
                title: '用户信息' + title,
            },
        },
    ],
});

axios.get('/admin/menu/load').then(res => {
    let menu = res.data.data.menuInfo;
    if(res.data.code === 1) {
        for (let index = 0; index < menu.length; index++) {
            for (let sub_index = 0; sub_index < menu[index].child.length; sub_index++) {
                if(menu[index].child[sub_index].child.length) {
                    for (let sub_index2 = 0; sub_index2 < menu[index].child[sub_index].child.length; sub_index2++) {
                        router.addRoute({
                            path: menu[index].child[sub_index].child[sub_index2].href,
                            name: menu[index].child[sub_index].child[sub_index2].name,
                            component: resolve => require(['@/views/admin/' + menu[index].child[sub_index].child[sub_index2].name], resolve),
                            meta: {
                                title: menu[index].child[sub_index].child[sub_index2].title + title,
                            },
                        });
                    }
                }else {
                    router.addRoute({
                        path: menu[index].child[sub_index].href,
                        name: menu[index].child[sub_index].name,
                        component: resolve => require(['@/views/admin/' + menu[index].child[sub_index].name], resolve),
                        meta: {
                            title: menu[index].child[sub_index].title + title,
                        },
                    });
                }
            }
        }
    }
});

export default router;
