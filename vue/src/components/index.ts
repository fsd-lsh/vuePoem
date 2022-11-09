import vpAdmin from './vpAdmin.vue';
import vpNavTabs from "./vpNavTabs.vue";
import vpTable from "./vpTable.vue";
import vpTableSearch from "./vpTableSearch.vue";
import vpConfig from "./vpConfig.vue";

export default {
    install(app) {
        app.component('vpAdmin', vpAdmin);
        app.component('vpNavTabs', vpNavTabs);
        app.component('vpTable', vpTable);
        app.component('vpTableSearch', vpTableSearch);
        app.component('vpConfig', vpConfig);
    },
}
