import axios from "axios";
export default {

    methods: {

        //ajax请求封装
        poemRequest(options) {

            let request;
            switch (options.type) {
                case 'get': { request = axios.get; break; }
                case 'post': { request = axios.post; break; }
            }

            request(
                options.url,
                options.data
            )
            .then(options.success)
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
