<template>
    <div id="app">
        <router-view/>
        <footer>Design By <a target="_blank" href="https://www.easybhu.cn">Force</a></footer>
    </div>
</template>

<script lang="js">

import helper from "./mixins/helper";
import theme from "./mixins/theme";

export default {

    name: 'App',

    mixins: [helper, theme],

    beforeCreate() {

        //Vuex State
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

        //load theme
        this.loadTheme();

        //sysInfo
        console.log(
            `%c Design By Force %c VuePoem %c`,
            'background:#35495e ; padding: 1px; border-radius: 3px 0 0 3px;  color: #fff',
            'background:#1aa094 ; padding: 1px; border-radius: 0 3px 3px 0;  color: #fff',
            'background:transparent'
        )
    },
}
</script>

<style lang="less">
    @import "../../common/theme/public";
    @import "../../common/theme/theme";
</style>
