export default {

    data() {
        return {
            focusTab: '',
            tabs: [],
            menuInfo: [],
        }
    },

    created() {
        this.tabInit();
    },

    methods: {

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
                    title: title.slice(0, title.indexOf(' -')),
                    icon: this.$route.meta.icon,
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

        tabClick({name}) {
            if(name !== this.$route.name) {
                this.$router.push({name:name});
            }
        },

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

        tabClear() {
            this.tabs = [];
            window.sessionStorage.setItem('tabs', JSON.stringify([]));
        },
    },

    watch: {
        'tabs'() {
            if(this.tabs.length > 15) {
                this.tabs = this.tabs.slice(1, this.tabs.length);
            }
            window.sessionStorage.setItem('tabs', JSON.stringify(this.tabs));
        },
    },
};
