<template>

    <div id="app">

        <router-view/>

        <footer>
            Design By <a target="_blank" href="https://www.easybhu.cn">Force</a>
        </footer>
    </div>
</template>

<script>

import helper from "./mixins/helper";

export default {

    name: 'App',

    mixins: [helper],

    data() {
        return {}
    },

    beforeCreate() {

        //Vuex State 预定义
        this.$store.replaceState(
            Object.assign({},
                this.$store.state,
                {

                    //登录状态
                    isSignIn: false,

                    //菜单树
                    menuTree: {},
                }
            )
        );
    },

    created() {

        //sysInfo
        console.log(
            `%c Design By Force %c VuePoem %c`,
            'background:#35495e ; padding: 1px; border-radius: 3px 0 0 3px;  color: #fff',
            'background:#1aa094 ; padding: 1px; border-radius: 0 3px 3px 0;  color: #fff',
            'background:transparent'
        )
    },

    methods: {},
}
</script>

<style lang="less">
    @import "../static/css/theme";
    @import "../static/css/public";
</style>
