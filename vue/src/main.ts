import { createApp } from 'vue';
import ElementPlus from 'element-plus';
import App from './App.vue';
import router from './router';
import store from './store';
import i18n from './i18n';
import myComponents from './components';

import 'element-plus/dist/index.css';
import 'font-awesome/css/font-awesome.min.css';
import 'nprogress/nprogress.css';
import '../static/css/reset.css';

const app = createApp(App);

app.use(ElementPlus);
app.use(store);
app.use(i18n);
app.use(myComponents);
app.use(router);

app.mount('#app');
