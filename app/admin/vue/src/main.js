// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import App from './App';
import router from './router';
import less from 'less';
import store from '../store';
import ElementUI from 'element-ui';
import VueI18n from 'vue-i18n';

import 'element-ui/lib/theme-chalk/index.css';
import 'font-awesome/css/font-awesome.min.css';

Vue.config.productionTip = false;

Vue.use(ElementUI);
Vue.use(less);
Vue.use(VueI18n);

//i18n
let lang = window.localStorage.getItem('sys-lang');
lang = lang ? lang : 'zh';
window.localStorage.setItem('sys-lang', lang);
const i18n = new VueI18n({
    locale: lang,
    messages: {
        'zh': require('@/assets/languages/zh.json'),
        'en': require('@/assets/languages/en.json')
    }
});

//路由切换-title更新
router.beforeEach((to, from, next) => {
    if (to.meta.title) {
        document.title = to.meta.title;
    }
    next();
})

//路由切换-自动返回页面顶部
router.afterEach(() => {
    window.scrollTo(0, 0);
});

//防止手动修改本地存储
window.addEventListener('storage', (e) => {
    sessionStorage.setItem(e.key, e.oldValue);
});

new Vue({
    el: '#app',
    router,
    i18n,
    store,
    components: {App},
    template: '<App/>',
})
