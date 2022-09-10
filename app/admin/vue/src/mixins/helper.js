import axios from "axios";
export default {

    data() {
        return {

            //主题列表
            themeList: [],

            //当前主题
            nowTheme: '',

            //Nav tab
            focusTab: '',
            tabs: [],
        }
    },

    created() {

        //加载store
        this.loadStore();

        //在页面刷新之前把vuex中的信息存到sessionStorage
        window.addEventListener("pagehide", () => {
            sessionStorage.setItem("store", JSON.stringify(this.$store.state));
        });

        //加载主题
        this.loadTheme();

        //Nav tab
        this.tabInit();
    },

    methods: {

        //ajax请求封装
        poemRequest(options) {

            let request;
            let lang = window.localStorage.getItem('sys-lang');

            switch (options.type) {
                case 'get': { request = axios.get; break; }
                case 'post': { request = axios.post; break; }
                case 'put': { request = axios.put; break; }
                case 'delete': { request = axios.delete; break; }
            }

            if(options.url.indexOf('?') === -1) {
                options.url = options.url + '?lang=' + lang;
            }else {
                options.url = options.url + '&lang=' + lang;
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

        //加载菜单
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

        //加载主题
        loadTheme() {

            let focusTheme = () => {
                if(this.in_array(window.localStorage.getItem('sys-theme'), this.themeList)) {
                    this.nowTheme = window.localStorage.getItem('sys-theme');
                }else {
                    this.nowTheme = this.themeList[0];
                }
            };

            if(window.sessionStorage.getItem('sys-theme-lists')) {

                this.themeList = JSON.parse(window.sessionStorage.getItem('sys-theme-lists'));
                focusTheme();

            }else {

                this.poemRequest({
                    type: 'get',
                    url: '/admin/index/theme?api=load',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.themeList = res.data.data;
                            window.sessionStorage.setItem('sys-theme-lists', JSON.stringify(res.data.data));
                            focusTheme();
                        }else {
                            this.$notify({message: res.data.info, type: 'warning'});
                        }
                    },
                });
            }
        },

        //页面每次刷新加载时候都会去读取sessionStorage里面的vuex状态
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

        //获取GET请求
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

        //js版in_array
        in_array(search, array) {
            for(var i in array) {
                if(array[i] == search){
                    return true;
                }
            }
            return false;
        },

        //NavTab 初始化
        tabInit() {

            let tabs = JSON.parse(window.sessionStorage.getItem('tabs'));
            if(tabs) {
                this.tabs = tabs;
            }
            this.focusTab = this.$route.name;
            if(this.$route.name && this.$route.name !== 'signIn') {
                let title = this.$route.meta.title;
                this.tabs.push({
                    name: this.$route.name,
                    title: title.slice(0, title.indexOf(' -'))
                });
            }

            let map = new Map();
            for (let item of this.tabs) {
                if (!map.has(item.name)) {
                    map.set(item.name, item);
                }
            }
            this.tabs = [...map.values()];

            window.sessionStorage.setItem('tabs', JSON.stringify(this.tabs));
        },

        //navTab 点击回调
        tabClick({name}) {
            if(name !== this.$route.name) {
                this.$router.push({name:name});
            }
        },

        //navTab 移除回调
        tabRemove(name) {

            for (let index in this.tabs) {
                if(this.tabs[index].name === name) {
                    this.tabs.splice(index, 1);
                    break;
                }
            }

            window.sessionStorage.setItem('tabs', JSON.stringify(this.tabs));
            let autoFocusTab = this.tabs[0].name;
            if(this.$route.name === name && this.$route.name !== autoFocusTab) {
                this.tabClick({name:autoFocusTab});
            }
        },

        //navTab cache clear
        tabClear() {
            this.tabs = [];
            window.sessionStorage.setItem('tabs', JSON.stringify([]));
        },
    },
}
