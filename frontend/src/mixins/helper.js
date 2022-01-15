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
    },
}
