import {createRouter, createWebHashHistory} from 'vue-router';
import axios from 'axios';
import NProgress from 'nprogress';
import i18n from '../i18n';

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            name: 'signIn',
            component: () => import('../views/signIn.vue'),
            meta: {
                title: '',
            },
        },
    ]
});

const lang = localStorage.getItem('sys-lang')
await axios.get('/admin/menu/load?lang='+lang).then(res => {

    if(res.data.code === 955) {
        sessionStorage.setItem('store', JSON.stringify({isSignIn:false,menuTree:{}}));
        router.push('/');
        return false;
    }

    if(res.data.code === 1) {

        let menu = res.data.data.menuInfo;
        let logoInfo = res.data.data.logoInfo;

        if(window.sessionStorage.getItem('store')) {
            let store = JSON.parse(<string>window.sessionStorage.getItem('store'));
            store.menuTree.menuInfo = menu;
            window.sessionStorage.setItem('store', JSON.stringify(store));
        }else {
            window.sessionStorage.setItem('store', JSON.stringify([]));
        }

        for (let index = 0; index < menu.length; index++) {
            for (let sub_index = 0; sub_index < menu[index].child.length; sub_index++) {
                if(menu[index].child[sub_index].child.length) {
                    for (let sub_index2 = 0; sub_index2 < menu[index].child[sub_index].child.length; sub_index2++) {
                        router.addRoute({
                            path: menu[index].child[sub_index].child[sub_index2].href,
                            name: menu[index].child[sub_index].child[sub_index2].name,
                            component: () => import(`../views/admin/${menu[index].child[sub_index].child[sub_index2].name}.vue`),
                            meta: {
                                title: menu[index].child[sub_index].child[sub_index2].title + ' - ' + logoInfo.title,
                                icon: menu[index].child[sub_index].icon,
                            }
                        });
                    }
                }else {
                    router.addRoute({
                        path: menu[index].child[sub_index].href,
                        name: menu[index].child[sub_index].name,
                        component: () => import(`../views/admin/${menu[index].child[sub_index].name}.vue`),
                        meta: {
                            title: menu[index].child[sub_index].title + ' - ' + logoInfo.title,
                            icon: menu[index].child[sub_index].icon,
                        }
                    });
                }
            }
        }
    }
})

router.beforeEach((to, from, next) => {
    if(window.sessionStorage.getItem('store')) {
        let store = JSON.parse(<string>window.sessionStorage.getItem('store'));
        if(to.path === '/' && store.isSignIn === true) {
            router.push('/dash');
        }
    }
    if (to.meta.title) {
        // @ts-ignore
        window.document.title = to.meta.title;
    }
    switch (to.path) {
        case '/': {
            window.document.title = i18n.global.t('admin.signIn.signIn');
            break;
        }
        case '/userInfo': {
            window.document.title = i18n.global.t('admin.user.userInfo');
            break;
        }
    }
    NProgress.start();
    next();
});

router.afterEach(() => {
    window.scrollTo(0, 0);
    NProgress.done();
});

export default router;
