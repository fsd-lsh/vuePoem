<template>

    <el-row :class="nowTheme">

        <!--左侧部分（logo、二级菜单）-->
        <el-col
            :span="this.$store.state.isSignIn ? 3 : 0"
            v-if="this.$store.state.isSignIn"
            id="left">

            <!--logo-->
            <div class="logo">
                <i class="fa fa-chrome">&nbsp;&nbsp;</i><b>Vue<i>Poem</i></b>
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
                        v-if="item.child.length"
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
                        v-if="!item.child.length"
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
                        :span="24">{{$t('admin.public.setTheme')}}</el-col>
                    <el-col
                        :span="8"
                        :key="key"
                        v-for="(item, key) in themeList"
                        @click.native="themeHandleChange(item)"
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
                <el-radio-button :label="false">{{$t('admin.public.open')}}</el-radio-button>
                <el-radio-button :label="true">{{$t('admin.public.close')}}</el-radio-button>
            </el-radio-group>

            <!--一级菜单-->
            <el-menu
                v-show="this.$store.state.isSignIn"
                :default-active="activeIndex"
                class="menu-level-1"
                mode="horizontal"
                @select="menuHandleSelect"
                background-color="var(--sys-main-color)"
                text-color="#fff"
                style="width:100%!important;"
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
                    <el-tooltip class="item" effect="dark" :content="$t('admin.public.config')" placement="bottom">
                        <i class="fa fa-ellipsis-v"></i>
                    </el-tooltip>
                </el-menu-item>
                <el-submenu
                    index="2"
                    class="pull-right">
                    <template slot="title">{{ username }}</template>
                    <el-menu-item
                        @click="openTools('modAccount')"
                        index="2-1">{{$t('admin.public.modAccount')}}
                    </el-menu-item>
                    <el-menu-item
                        @click="openTools('signOut')"
                        index="2-2">{{$t('admin.public.signOut')}}
                    </el-menu-item>
                </el-submenu>
                <el-menu-item
                    @click="openTools('fullScreen')"
                    class="pull-right">
                    <el-tooltip class="item" effect="dark" :content="$t('admin.public.fullScreen')" placement="bottom">
                        <i v-if="!iFullscreen" class="fa fa-arrows-alt"></i>
                    </el-tooltip>
                    <el-tooltip class="item" effect="dark" :content="$t('admin.public.exitFullScreen')" placement="bottom">
                        <i v-if="iFullscreen" class="fa fa-compress"></i>
                    </el-tooltip>
                </el-menu-item>
                <el-menu-item
                    @click="openTools('clean')"
                    class="pull-right">
                    <el-tooltip class="item" effect="dark" :content="$t('admin.public.clear')" placement="bottom">
                        <i class="fa fa-trash-o"></i>
                    </el-tooltip>
                </el-menu-item>
                <el-menu-item
                    @click="openTools('language')"
                    class="pull-right">
                    <el-tooltip class="item" effect="dark" :content="$t('admin.public.switchLanguage')" placement="bottom">
                        <i class="fa fa-language"></i>
                    </el-tooltip>
                </el-menu-item>
            </el-menu>

            <!--hot view-->
            <slot/>
        </el-col>
    </el-row>
</template>

<script>
import helper from "../mixins/helper";

export default {

    name: 'admin',

    mixins: [helper],

    data() {
        return {
            activeIndex: '1',
            activeSubIndex: '0',
            pid: 0,
            id: 0,
            subMenuTree: {},
            username: '',
            iFullscreen: false,
            drawer: false,
            isCollapse: false,
            leftViewSpan: 3,
            rightViewSpan: 21,
        }
    },

    created() {

        //定位menu active
        if (this.$store.state.isSignIn) {
            this.positionMenu();
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
                        this.$notify({message:this.$t('admin.public.fullScreenErr'), type:'warning'});
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
                                sessionStorage.setItem('store', JSON.stringify({isSignIn:false,menuTree:{}}));
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

                //语言切换
                case 'language': {

                    let lang = window.localStorage.getItem('sys-lang');
                    switch (lang) {
                        case 'en': {
                            window.localStorage.setItem('sys-lang', 'zh');
                            break;
                        }
                        case 'zh':
                        default: {
                            window.localStorage.setItem('sys-lang', 'en');
                            break;
                        }
                    }
                    window.location.reload();
                    break;
                }
            }
        },

        //一级菜单点击回调
        menuHandleSelect(tree_key, onSelect) {

            //clear
            this.activeSubIndex = '0';

            //刷新下级菜单
            let menuTree = this.$store.state.menuTree.menuInfo;
            for (let key = 0; key < menuTree.length; key++) {
                if (parseInt(menuTree[key].id) === parseInt(tree_key)) {
                    this.subMenuTree = menuTree[key];
                    if(!onSelect) {
                        //刷新

                    }else {
                        //点击
                        this.$router.push(this.subMenuTree.child[0].href);
                    }
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
                            this.menuHandleSelect(this.pid, false);
                            this.activeIndex = this.pid;
                            this.activeSubIndex = this.id;
                        }
                    }
                }
            }
        },

        //切换页面
        openMenu(item) {
            this.$router.push(item.href);
        },

        //主题面板切换
        themeHandleChange(themeClassName) {

            if(!this.in_array(themeClassName, this.themeList)) {
                this.$notify({ message:this.$t('admin.public.themeNotFound'), type:'error'});
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

    watch: {

        '$store.state.menuTree.menuInfo'() {
            this.positionMenu();
        },

        isCollapse() {

            if(this.$store.state.isSignIn) {

                if(isCollapse) {
                    this.leftViewSpan = 1;
                    this.rightViewSpan = 23;
                }else {
                    this.leftViewSpan = 3;
                    this.rightViewSpan = 21;
                }
            }else {
                this.leftViewSpan = 3;
                this.rightViewSpan = 24;
            }
        },
    },

    computed: {
        key() {
            return this.$route.name !== undefined? this.$route.name + +new Date(): this.$route + +new Date()
        }
    }
}
</script>

<style scoped lang="less">

</style>
