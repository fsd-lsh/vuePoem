<template>

    <div id="app" :class="nowTheme">

        <el-row>

            <!--左侧部分（logo、二级菜单）-->
            <el-col :span="3" id="left" v-if="this.$store.state.isSignIn">

                <!--logo-->
                <div class="logo">
                    <i class="fa fa-chrome">&nbsp;&nbsp;</i><b>Admin<i>Poem2</i></b>
                </div>

                <!--二级菜单-->
                <el-menu
                    :default-active="activeSubIndex"
                    class="menu-level-2"
                    @open="handleOpen"
                    @close="handleClose"
                    background-color="var(--sys-main-menu-color2)"
                    text-color="#fff"
                    :collapse="isCollapse">
                    <div
                        v-for="(item, key) in this.subMenuTree.child"
                        :key="key">

                        <!--多级-->
                        <el-submenu
                            v-if="item.child"
                            :index="item.id">
                            <template
                                slot="title">
                                <i :class="item.icon"></i>
                                <span slot="title">&nbsp;&nbsp;&nbsp;{{ item.title }}</span>
                            </template>
                            <el-menu-item-group>
                                <el-menu-item
                                    @click="openMenu(s_item)"
                                    v-for="(s_item, s_key) in item.child"
                                    :key="s_key"
                                    :index="s_item.pid + '-' + s_item.id">
                                    {{ s_item.title }}
                                </el-menu-item>
                            </el-menu-item-group>
                        </el-submenu>

                        <!--单级-->
                        <el-menu-item
                            @click="openMenu(item)"
                            v-if="!item.child"
                            :key="key"
                            :index="item.id">
                            <i :class="item.icon"></i>
                            <span
                                slot="title">
                                &nbsp;&nbsp;&nbsp;{{ item.title }}
                            </span>
                        </el-menu-item>
                    </div>
                </el-menu>

                <!--配置面板-->
                <el-drawer
                    :visible.sync="drawer"
                    :show-close="true"
                    :with-header="false">
                    <el-row
                        id="config-panel"
                        :gutter="20">
                        <el-col
                            class="config-panel-title"
                            :span="24">设置主题</el-col>
                        <el-col
                            :span="8"
                            :key="key"
                            v-for="(item, key) in themeList"
                            @click.native="themeHandleClose(item)"
                            class="config-panel-item">
                            <div
                                :class="{
                                    'grid-content': true,
                                    'active': (nowTheme === item),
                                }">
                                <el-tooltip class="item" effect="dark" :content="item" placement="bottom">
                                    <div class="text">{{item}}</div>
                                </el-tooltip>
                            </div>
                        </el-col>
                    </el-row>
                </el-drawer>
            </el-col>

            <!--右侧部分（一级菜单、路由视图）-->
            <el-col
                :span="this.$store.state.isSignIn ? 21 : 24"
                :class="{
                    'inner-view': this.$store.state.isSignIn,
                }"
                id="right">

                <!--展开收起左侧菜单-->
                <el-radio-group v-if="0" v-model="isCollapse" style="margin-bottom: 20px;">
                    <el-radio-button :label="false">展开</el-radio-button>
                    <el-radio-button :label="true">收起</el-radio-button>
                </el-radio-group>

                <!--一级菜单-->
                <el-menu
                    v-if="this.$store.state.isSignIn"
                    :default-active="activeIndex"
                    class="menu-level-1"
                    mode="horizontal"
                    @select="menuHandleSelect"
                    background-color="var(--sys-main-color)"
                    text-color="#fff"
                    active-text-color="var(--sys-main-ft-color2)">
                    <el-menu-item
                        v-for="(item, key) in this.$store.state.menuTree.menuInfo"
                        :key="item.id"
                        :index="item.id">
                        {{ item.title }}
                    </el-menu-item>
                    <el-menu-item
                        @click="openTools('configPanel')"
                        class="pull-right">
                        <i class="fa fa-ellipsis-v"></i>
                    </el-menu-item>
                    <el-submenu
                        index="2"
                        class="pull-right">
                        <template slot="title">{{ username }}</template>
                        <el-menu-item
                            @click="openTools('modAccount')"
                            index="2-1">修改账户
                        </el-menu-item>
                        <el-menu-item
                            @click="openTools('signOut')"
                            index="2-2">退出登录
                        </el-menu-item>
                    </el-submenu>
                    <el-menu-item
                        @click="openTools('fullScreen')"
                        class="pull-right">
                        <el-tooltip class="item" effect="dark" content="全屏展开" placement="bottom">
                            <i v-if="!iFullscreen" class="fa fa-arrows-alt"></i>
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="全屏收起" placement="bottom">
                            <i v-if="iFullscreen" class="fa fa-compress"></i>
                        </el-tooltip>
                    </el-menu-item>
                    <el-menu-item
                        @click="openTools('clean')"
                        class="pull-right">
                        <i class="fa fa-trash-o"></i>
                    </el-menu-item>
                </el-menu>

                <!--路由视图-->
                <router-view/>
            </el-col>
        </el-row>

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
        return {
            isCollapse: false,
            activeIndex: '1',
            activeSubIndex: '0',
            pid: 0,
            id: 0,
            subMenuTree: {},
            username: '',
            iFullscreen: false,
            drawer: false,
            themeList: [
                'sys-theme-default',
                'sys-theme-admin-poem',
                'sys-theme-green',
            ],
            nowTheme: '',
        }
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

        //定位menu active
        if (this.$store.state.isSignIn) {
            this.positionMenu();
        }

        //默认主题
        if(this.in_array(window.localStorage.getItem('sys-theme'), this.themeList)) {
            this.nowTheme = window.localStorage.getItem('sys-theme');
        }else {
            this.nowTheme = this.themeList[0];
        }

        //写入用户信息
        this.username = sessionStorage.getItem('username');
    },

    updated() {

        //定位menu active
        if(this.pid !== this.activeIndex) {
            this.positionMenu();
        }
    },

    methods: {

        //切换页面
        openMenu(item) {
            this.$router.push(item.href);
        },

        //工具组
        openTools(type) {

            switch (type) {

                //清理缓存
                case 'clean': {
                    this.poemRequest({
                        type: 'post',
                        url: '/admin/dash?api=clear_cache',
                        success: (res) => {
                            if (res.data.code === 1) {
                                this.$notify({message: res.data.info, type: 'success'});
                            } else {
                                this.$notify({message: res.data.info, type: 'warning'});
                            }
                        },
                    });
                    break;
                }

                //全屏
                case 'fullScreen': {

                    let fullscreenEnabled = document.fullscreenEnabled ||
                        document.mozFullScreenEnabled ||
                        document.webkitFullscreenEnabled ||
                        document.msFullscreenEnabled;

                    if (fullscreenEnabled) {
                        let de = document.documentElement;
                        if (this.iFullscreen) {
                            //关闭全屏
                            if (document.exitFullscreen) {
                                document.exitFullscreen();
                            } else if (document.mozCancelFullScreen) {
                                document.mozCancelFullScreen();
                            } else if (document.webkitCancelFullScreen) {
                                document.webkitCancelFullScreen();
                            }
                            this.iFullscreen = false;
                        } else {
                            //打开全屏
                            if (de.requestFullscreen) {
                                de.requestFullscreen();
                            } else if (de.mozRequestFullScreen) {
                                de.mozRequestFullScreen();
                            } else if (de.webkitRequestFullScreen) {
                                de.webkitRequestFullScreen();
                            }
                            this.iFullscreen = true;
                        }
                    } else {
                        this.$notify({message: '浏览器当前不能全屏', type: 'warning'});
                    }
                    break;
                }

                //修改账号信息
                case 'modAccount': {

                    this.$router.push('/userInfo');
                    break;
                }

                //注销系统
                case 'signOut': {
                    this.poemRequest({
                        type: 'post',
                        url: '/admin?api=sign_out',
                        success: (res) => {
                            if (res.data.code === 1) {
                                this.$store.commit('changeSignInState', false);
                                this.$router.push(res.data.data);
                                this.$notify({message: res.data.info, type: 'success'});
                            } else {
                                this.$notify({message: res.data.info, type: 'warning'});
                            }
                        },
                    });
                    break;
                }

                //配置面板
                case 'configPanel': {
                    this.drawer = true;
                    break;
                }
            }
        },

        //一级菜单点击回调
        menuHandleSelect(tree_key) {

            //clear
            this.activeSubIndex = '0';

            //刷新下级菜单
            let menuTree = this.$store.state.menuTree.menuInfo;
            for (let key = 0; key < menuTree.length; key++) {
                if (parseInt(menuTree[key].id) === parseInt(tree_key)) {
                    this.subMenuTree = menuTree[key];
                    break;
                }
            }
        },

        //定位menu active
        positionMenu() {
            let menuTree = this.$store.state.menuTree.menuInfo;
            for (let key in menuTree) {
                if (menuTree[key].child.length) {
                    for (let s_key in menuTree[key].child) {
                        if (menuTree[key].child[s_key].href === this.$route.path) {
                            this.pid = menuTree[key].child[s_key].pid;
                            this.id = menuTree[key].child[s_key].id;
                            this.menuHandleSelect(this.pid);
                            this.activeIndex = this.pid;
                            this.activeSubIndex = this.id;
                        }
                    }
                }
            }
        },

        //主题面板关闭
        themeHandleClose(themeClassName) {

            if(!this.in_array(themeClassName, this.themeList)) {
                this.$notify({ message:'主题不存在', type:'error'});
                return false;
            }
            window.localStorage.setItem('sys-theme', themeClassName);
            this.nowTheme = themeClassName;
        },

        handleOpen(key, keyPath) {
            //console.log(key, keyPath);
        },

        handleClose(key, keyPath) {
            //console.log(key, keyPath);
        },
    },
}
</script>

<style lang="less">

    @import "../static/css/theme";

    #app {

        font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;

        > .el-row {

            height: 100%;

            #left {

                height: 100%;
                background: var(--sys-main-menu-color2);

                > .el-menu {
                    border: none;
                }

                > .logo {

                    width: 100%;
                    height: 60px;
                    background: var(--sys-main-menu-color);
                    text-align: center;
                    font-weight: 600;
                    font-size: 1.4rem;
                    line-height: 60px;
                    color: #fff;

                    i {
                        font-size: 1.2rem;
                        color: var(--sys-main-ft-color);
                    }

                    > b {
                        font-size: 1.2rem;
                    }
                }

                > .menu-level-2 {

                    .el-submenu {

                        .el-menu-item-group {

                            .el-menu-item-group__title {

                            }
                        }
                    }

                    .el-menu-item {

                        min-width: 100%;
                        width: 100%;

                        &.is-active {
                            color: var(--sys-main-ft-color)!important;
                        }
                    }

                    .el-menu-item:hover {

                        background: var(--sys-main-color)!important;
                        color: #fff!important;

                        i {
                            color: #fff!important;
                        }
                    }

                    span {
                        font-size: .6rem;
                    }

                    .el-submenu__title,
                    .el-menu-item {
                        text-align: left;
                    }

                    li.el-menu-item {
                        font-size: .8rem;
                    }
                }

                #config-panel {

                    padding: 10px;

                    .config-panel-title {
                        color: var(--sys-main-ft-color);
                        font-size: 1rem;
                        font-weight: 600;
                    }

                    .config-panel-item {

                        margin: 1rem 0 0 0;
                        padding: 1.2rem;
                        height: 70px;
                        vertical-align:middle;
                        text-align: center;
                        transition: all linear .3s;
                        cursor: pointer;

                        .grid-content {

                            display: block;
                            height: 100%;
                            line-height: 100%;
                            width: 100%;
                            border: 1px solid var(--sys-main-ft-color);
                            border-radius: 5px;
                            font-weight: 600;
                            font-size: .9rem;

                            &:hover {
                                transition: all linear .3s;
                                color: #fff;
                                background: var(--sys-main-ft-color);
                            }

                            div.text {

                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                padding: .8rem;
                            }
                        }

                        > .active {
                            transition: all linear .3s;
                            color: #fff;
                            background: var(--sys-main-ft-color);
                        }
                    }
                }
            }

            #right {

                &.inner-view {
                    height: 100%;
                    background: #fff;
                }

                > .el-menu {
                    border: none;
                }

                > div:nth-child(2) {
                    padding: 1%;
                }

                .menu-level-1 {

                    color: #fff!important;

                    > .el-menu-item {

                        > i {
                            color: #fff!important;
                        }
                    }

                    .el-menu-item:hover {
                        background: var(--sys-main-color)!important;
                    }

                    .el-submenu {

                        .el-submenu__title {

                            i {
                                color: #fff!important;
                            }

                            &:hover {
                                background: var(--sys-main-color)!important;
                            }
                        }
                    }
                }
            }
        }

        footer {
            font-size: .6rem;
            text-align: center;
            color: #8D8D8D;
            font-weight: 600;
            display: block;
            position: fixed;
            bottom: 10px;
            left: 0;
            width: 100%;

            a {
                color: #8D8D8D;
                text-decoration: none;
                font-weight: 800;
            }
        }
    }

    .menu-level-2:not(.el-menu--collapse) {
        min-height: 400px;
        width: 100%;
    }

    .el-menu-item-group__title {
        padding: 0;
    }

    .pull-right {
        float: right !important;
    }

    body > .el-menu--horizontal {
        background: #23262e!important;
    }
</style>
