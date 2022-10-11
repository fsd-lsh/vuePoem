export default {

    data() {
        return {
            //主题列表
            themeList: [],

            //当前主题
            nowTheme: '',
        }
    },

    created() {

        //加载主题
        this.loadTheme();
    },

    methods: {

        //加载主题
        loadTheme() {
            let focusTheme = () => {
                if(this.in_array(window.localStorage.getItem('sys-theme'), this.themeList)) {
                    this.nowTheme = window.localStorage.getItem('sys-theme');
                }else {
                    this.nowTheme = this.themeList[0];
                }
                document.querySelector('body').setAttribute('class', this.nowTheme);
            };

            if(
                window.sessionStorage.getItem('sys-theme-lists') &&
                JSON.parse(window.sessionStorage.getItem('sys-theme-lists')).length !== 0
            ) {

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

        //主题面板切换
        themeHandleChange(themeClassName) {

            if(!this.in_array(themeClassName, this.themeList)) {
                this.$notify({ message:this.$t('admin.public.themeNotFound'), type:'error'});
                return false;
            }
            window.localStorage.setItem('sys-theme', themeClassName);
            this.nowTheme = themeClassName;
            document.querySelector('body').setAttribute('class', this.nowTheme);
        },
    },
};
