import axios from "axios";
export default {

    created() {

        //页面每次刷新加载时候都会去读取sessionStorage里面的vuex状态
        if (sessionStorage.getItem("store")) {
            this.$store.replaceState(
                Object.assign({},
                    this.$store.state,
                    JSON.parse(sessionStorage.getItem("store")) //这里存的是可能经过mutions处理过的state值，是最新的,所以放在最后
                )
            );
        }

        // 在页面刷新之前把vuex中的信息存到sessionStorage
        window.addEventListener("pagehide", () => {
            sessionStorage.setItem("store", JSON.stringify(this.$store.state));
        });
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
                }else {
                    //this.$notify.error({message: '服务器发生异常'});
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
                },
            });
        },

        //获取GET请求
        parseGET() {

            let url = window.document.location.href.toString();   //当前完整url
            let u = url.split("?");                               //以？为分隔符把url转换成字符串数组

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

        //js 版 in_array
        in_array(search, array) {
            for(var i in array) {
                if(array[i] == search){
                    return true;
                }
            }
            return false;
        }
    },
}
