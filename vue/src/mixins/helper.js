import axios from "axios";
export default {

    data() {
        return {
            loading: false,
        }
    },

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

            this.loading = true;
            axios({
                method: options.type,
                url: options.url,
                data: options.data
            }).then((res) => {
                if(res.data.code === 955) {
                    this.$store.commit('changeSignInState', false);
                    let store = JSON.parse(sessionStorage.getItem('store'));
                    store.isSignIn = false;
                    store.menuTree = {};
                    sessionStorage.setItem('store', JSON.stringify(store));
                    this.$router.push('/');
                }
                options.success(res);
                this.loading = false;
            }).catch((res) => {
                this.loading = false;
                if(options.error) {
                    options.error;
                }else {
                    this.$alert(res.response.data, res.code, {dangerouslyUseHTMLString:true});
                    console.log('--------------------------API ERR:' + options.url);
                    console.warn(res);
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

        hexToRgba(hex, opacity) {
            if(!hex) {
                return false;
            }
            if(!opacity) {
                opacity = 0;
            }
            let RGBA = "rgba(" + parseInt("0x" + hex.slice(1, 3)) + "," + parseInt("0x" + hex.slice(3, 5)) + "," + parseInt( "0x" + hex.slice(5, 7)) + "," + opacity + ")";
            return {
                r: parseInt("0x" + hex.slice(1, 3)),
                g: parseInt("0x" + hex.slice(3, 5)),
                b: parseInt("0x" + hex.slice(5, 7)),
                rgba: RGBA
            }
        },

        liteNotice(code, info, mode) {
            if(mode === 'notify') {
                switch (code) {
                    case 0: {
                        this.$notify.error({ title:'Error', message:info, position:'bottom-right' });
                        break;
                    }
                    case 1: {
                        this.$notify({ title:'Success', message:info, type:'success', position:'bottom-right' });
                        break;
                    }
                    case 2: {
                        this.$notify({ title:'Warning', message:info, type:'warning', position:'bottom-right' });
                        break;
                    }
                    default: {
                        return false;
                    }
                }
            }else {
                switch (code) {
                    case 0: {
                        this.$message.error(info);
                        break;
                    }
                    case 1: {
                        this.$message({ message:info, type:'success'} );
                        break;
                    }
                    case 2: {
                        this.$message({ message:info, type:'warning'} );
                        break;
                    }
                    default: {
                        return false;
                    }
                }
            }
        },
    },
}
