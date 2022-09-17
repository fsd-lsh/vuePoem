export default {

    data() {
        return {
            //Nav tab
            focusTab: '',
            tabs: [],
        }
    },

    created() {

        //Nav tab
        this.tabInit();
    },

    methods: {

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
        tabRemove({name}) {

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
};
