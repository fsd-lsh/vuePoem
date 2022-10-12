import axios from "axios";
export default {

    created() {

        //加载store
        this.loadStore();

        //在页面刷新之前把vuex中的信息存到sessionStorage
        window.addEventListener("pagehide", () => {
            sessionStorage.setItem("store", JSON.stringify(this.$store.state));
        });
    },

    methods: {

        poemRequest(options) {

            let request;

            switch (options.type) {
                case 'get': { request = axios.get; break; }
                case 'post': { request = axios.post; break; }
                case 'put': { request = axios.put; break; }
                case 'delete': { request = axios.delete; break; }
            }

            request(
                options.url,
                options.data
            )
            .then((res) => {
                if(res.data.code === 955) {
                    this.$store.commit('changeSignInState', false);
                    let store = JSON.parse(sessionStorage.getItem('store'));
                    store.isSignIn = false;
                    store.menuTree = {};
                    sessionStorage.setItem('store', JSON.stringify(store));
                    this.$router.push('/');
                }
                options.success(res);
            })
            .catch(() => {

                if(options.error) {
                    options.error;
                }
            });
        },

        loadMenu() {

            this.poemRequest({
                type: 'get',
                url: '/admin/menu/load',
                success: (res) => {
                    this.$store.commit('setMenuTree', res.data.data);
                    let store = window.sessionStorage.getItem('store');
                    if(store) {
                        store = JSON.parse(store);
                        store.menuTree = res.data.data;
                        window.sessionStorage.setItem('store', JSON.stringify(store));
                    }
                },
            });
        },

        loadStore() {
            if (sessionStorage.getItem("store")) {
                this.$store.replaceState(
                    Object.assign({},
                        this.$store.state,
                        JSON.parse(sessionStorage.getItem("store")) //这里存的是可能经过mutions处理过的state值，是最新的,所以放在最后
                    )
                );
            }
        },

        parseGET() {

            let url = window.document.location.href.toString();
            let u = url.split("?");

            if(typeof(u[1]) == "string"){

                u = u[1].split("&");
                var get = {};
                for(var i in u){
                    var j = u[i].split("=");
                    get[j[0]] = j[1];
                }
                return get;

            } else {

                return {};
            }
        },

        in_array(search, array) {
            for(var i in array) {
                if(array[i] == search){
                    return true;
                }
            }
            return false;
        },

        switchLang() {
            let lang = window.localStorage.getItem('sys-lang');
            switch (lang) {
                case 'en': {
                    window.localStorage.setItem('sys-lang', 'zh');
                    lang = 'zh';
                    break;
                }
                case 'zh':
                default: {
                    window.localStorage.setItem('sys-lang', 'en');
                    lang = 'en';
                    break;
                }
            }
            this.poemRequest({
                type: 'post',
                url: '/admin?api=lang',
                data: {lang: lang},
                success: (res) => {
                    if(res.data.code === 1) {
                        this.$router.go(0);
                    }else {
                        this.$notify.error({message:res.data.info});
                    }
                },
            });
        },
    },
}
