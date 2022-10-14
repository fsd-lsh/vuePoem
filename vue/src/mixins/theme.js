export default {

    data() {
        return {
            themeList: [],
            nowTheme: '',
        }
    },

    created() {
        this.loadTheme();
    },

    methods: {

        focusTheme() {
            if(this.in_array(window.localStorage.getItem('sys-theme'), this.themeList)) {
                this.nowTheme = window.localStorage.getItem('sys-theme');
            }else {
                this.nowTheme = this.themeList[0];
            }
            document.querySelector('body').setAttribute('class', this.nowTheme);
        },

        loadTheme(force) {

            if(
                window.sessionStorage.getItem('sys-theme-lists') &&
                JSON.parse(window.sessionStorage.getItem('sys-theme-lists')).length !== 0 &&
                this.themeList.length !== 0 &&
                !force
            ) {
                this.focusTheme();
                this.refGlobalStyle();
            }else {

                this.poemRequest({
                    type: 'get',
                    url: '/admin/index/theme?api=load',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.themeList = res.data.data;
                            window.sessionStorage.setItem('sys-theme-lists', JSON.stringify(res.data.data));
                            this.focusTheme();
                            this.refGlobalStyle();
                        }else {
                            this.$notify({message: res.data.info, type: 'warning'});
                        }
                    },
                });
            }
        },

        themeHandleChange(themeClassName) {
            if(!this.in_array(themeClassName, this.themeList)) {
                this.$notify({ message:this.$t('admin.public.themeNotFound'), type:'error'});
                return false;
            }
            window.localStorage.setItem('sys-theme', themeClassName);
            this.nowTheme = themeClassName;
            document.querySelector('body').setAttribute('class', this.nowTheme);
        },

        refGlobalStyle() {

            let custom = JSON.parse(window.localStorage.getItem('sys-theme-custom'));
            let head = document.getElementsByTagName('head');
            let styleLabel = document.createElement('style');
            let sysThemeCustom = document.getElementById('sys-theme-custom');
            let sysThemeLists = JSON.parse(window.sessionStorage.getItem('sys-theme-lists'));
            if(sysThemeCustom) {
                head.item(0).removeChild(document.getElementById('sys-theme-custom'));
            }
            styleLabel.type = 'text/css';
            styleLabel.id = 'sys-theme-custom';
            for (let key in custom) {
                styleLabel.innerHTML += custom[key];
                sysThemeLists.push(key);
            }
            head.item(0).appendChild(styleLabel);
            sysThemeLists = sysThemeLists.filter((v, i, s) => {
                return s.indexOf(v) === i;
            });
            window.sessionStorage.setItem('sys-theme-lists', JSON.stringify(sysThemeLists));

            this.themeList = JSON.parse(window.sessionStorage.getItem('sys-theme-lists'));
        },
    },
};
