<template>

    <div id="app">

        <el-row>

            <!--左侧部分（logo、二级菜单）-->
            <el-col :span="3" id="left" v-if="this.$store.state.isSignIn">

                <!--logo-->
                <div class="logo">
                    AdminPoem2
                </div>

                <!--二级菜单-->
                <el-menu
                    default-active="1-2-1"
                    class="menu-level-2"
                    @open="handleOpen"
                    @close="handleClose"
                    background-color="#545c64"
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
                                <span slot="title">&nbsp;&nbsp;&nbsp;{{item.title}}</span>
                            </template>
                            <el-menu-item-group>
                                <el-menu-item
                                    v-for="(s_item, s_key) in item.child"
                                    :key="s_key"
                                    :index="s_item.pid + '-' + s_item.id">
                                    {{s_item.title}}
                                </el-menu-item>
                            </el-menu-item-group>
                        </el-submenu>

                        <!--单级-->
                        <el-menu-item
                            v-if="!item.child"
                            :key="key"
                            :index="item.id">
                            <i :class="item.icon"></i>
                            <span
                                slot="title">
                                &nbsp;&nbsp;&nbsp;{{item.title}}
                            </span>
                        </el-menu-item>
                    </div>
                </el-menu>
            </el-col>

            <!--右侧部分（一级菜单、路由视图）-->
            <el-col :span="this.$store.state.isSignIn ? 21 : 24" id="right">

                <!--展开收起左侧菜单-->
                <el-radio-group v-if="0" v-model="isCollapse" style="margin-bottom: 20px;">
                    <el-radio-button :label="false">展开</el-radio-button>
                    <el-radio-button :label="true">收起</el-radio-button>
                </el-radio-group>

                <!--一级菜单-->
                <el-menu
                    v-if="this.$store.state.isSignIn"
                    :default-active="activeIndex"
                    class="el-menu-demo"
                    mode="horizontal"
                    @select="menuHandleSelect"
                    background-color="#545c64"
                    text-color="#fff"
                    active-text-color="#ffd04b">
                    <el-menu-item
                        v-for="(item, key) in this.$store.state.menuTree.menuInfo"
                        :key="item.id"
                        :index="item.id">
                        {{item.title}}
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

export default {

    name: 'App',

    data() {
        return {
            isCollapse: false,
            activeIndex: '1',
            subMenuTree: {},
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
            )
        }

        // 在页面刷新之前把vuex中的信息存到sessionStorage
        window.addEventListener("pagehide", () => {
            sessionStorage.setItem("store", JSON.stringify(this.$store.state));
        });
    },

    methods: {

        //一级菜单点击回调
        menuHandleSelect(tree_key) {

            //刷新下级菜单
            let menuTree = this.$store.state.menuTree.menuInfo;
            for (let key = 0; key < menuTree.length; key++) {
                if(menuTree[key].id === tree_key) {
                    this.subMenuTree = menuTree[key];
                    break;
                }
            }
        },

        handleOpen(key, keyPath) {
            console.log(key, keyPath);
        },

        handleClose(key, keyPath) {
            console.log(key, keyPath);
        },
    },
}
</script>

<style>
    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
    }
</style>

<style lang="less">

    #app {

        > .el-row {

            height: 100%;

            #left {

                height: 100%;
                background: #545c64;

                > .el-menu {
                    border: none;
                }

                > .logo {
                    width: 100%;
                    height: 60px;
                    background: #fff;
                    text-align: center;
                    font-weight: 600;
                    font-size: 1.2rem;
                    line-height: 60px;
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
            }

            #right {

                > .el-menu {
                    border: none;
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
</style>
