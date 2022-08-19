import Vue from 'vue';
import Router from 'vue-router';
import axios from 'axios';

Vue.use(Router);

const title = ' - VuePoem';
const lang = localStorage.getItem('sys-lang');
console.log(Vue)
let router = new Router({

    mode:'hash',

    routes: [

        {
            path: '/',
            name: 'signIn',
            component: resolve => require(['@/views/signIn'], resolve),
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

axios.get('/admin/menu/load?lang='+lang).then(res => {

    if(res.data.code === 955) {
        sessionStorage.setItem('store', JSON.stringify({isSignIn:false,menuTree:{}}));
        if(window.location.hash !== '#/') {
            window.location.href = '/';
        }
        return false;
    }

    let menu = res.data.data.menuInfo;
    let logoInfo = res.data.data.logoInfo;
    let store = JSON.parse(window.sessionStorage.getItem('store'));
    store.menuTree.menuInfo = menu;
    window.sessionStorage.setItem('store', JSON.stringify(store));

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
                                title: menu[index].child[sub_index].child[sub_index2].title + ' - ' + logoInfo.title,
                            },
                        });
                    }
                }else {
                    router.addRoute({
                        path: menu[index].child[sub_index].href,
                        name: menu[index].child[sub_index].name,
                        component: resolve => require(['@/views/admin/' + menu[index].child[sub_index].name], resolve),
                        meta: {
                            title: menu[index].child[sub_index].title + ' - ' + logoInfo.title,
                        },
                    });
                }
            }
        }
    }
});

export default router;
