<template>

    <el-row>

        <!--左侧部分（logo、二级菜单）-->
        <el-col
            :span="this.leftViewSpan"
            v-if="this.$store.state.isSignIn"
            id="left">

            <!--logo-->
            <div class="logo">
                <img src="/favicon.ico" alt="logo" style="width:24px; height:24px; border-radius:100%;">
                <b v-if="!isCollapse">&nbsp;Vue<i>Poem</i></b>
            </div>

            <!--二级菜单-->
            <el-menu
                :default-active="activeSubIndex"
                class="menu-level-2"
                background-color="#23262e"
                text-color="#fff"
                :unique-opened="true"
                :collapse-transition="true"
                :collapse="isCollapse">
                <div
                    v-for="(item, key) in this.subMenuTree.child"
                    :key="key">

                    <!--多级-->
                    <el-submenu
                        v-if="item.child.length"
                        :show-timeout="200"
                        :hide-timeout="200"
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
                        :span="24">
                        {{$t('admin.public.setTheme')}}
                    </el-col>
                    <el-col
                        :span="8"
                        :key="key"
                        v-for="(theme, key) in themeList"
                        @click.native="themeHandleChange(theme)"
                        class="config-panel-item">
                        <div
                            :class="[
                                'grid-content',
                                theme,
                            ]">
                            <div class="top"></div>
                            <div class="left"></div>
                            <div class="right"></div>
                            <b class="fa fa-check-circle" v-if="(nowTheme === theme)"/>
                        </div>
                    </el-col>
                </el-row>
            </el-drawer>
        </el-col>

        <!--右侧部分（一级菜单、路由视图）-->
        <el-col
            :span="this.rightViewSpan"
            :class="{
                'inner-view': this.$store.state.isSignIn,
            }"
            id="right">

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
                    @click="toolsGroup('menu')">
                    <el-tooltip
                        class="item"
                        effect="dark"
                        :content="(isCollapse === true ? $t('admin.public.open') : $t('admin.public.close'))"
                        placement="bottom">
                        <i :class="{
                        'fa fa-indent': isCollapse,
                        'fa fa-outdent': !isCollapse,
                    }"/>
                    </el-tooltip>
                </el-menu-item>
                <el-menu-item
                    v-for="(item, key) in this.$store.state.menuTree.menuInfo"
                    :key="item.id"
                    :index="item.id">
                    {{ item.title }}
                </el-menu-item>
                <el-menu-item
                    @click="toolsGroup('configPanel')"
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
                        @click="toolsGroup('modAccount')"
                        index="2-1">{{$t('admin.public.modAccount')}}
                    </el-menu-item>
                    <el-menu-item
                        @click="toolsGroup('signOut')"
                        index="2-2">{{$t('admin.public.signOut')}}
                    </el-menu-item>
                </el-submenu>
                <el-menu-item
                    @click="toolsGroup('fullScreen')"
                    class="pull-right">
                    <el-tooltip class="item" effect="dark" :content="$t('admin.public.fullScreen')" placement="bottom">
                        <i v-if="!iFullscreen" class="fa fa-arrows-alt"></i>
                    </el-tooltip>
                    <el-tooltip class="item" effect="dark" :content="$t('admin.public.exitFullScreen')" placement="bottom">
                        <i v-if="iFullscreen" class="fa fa-compress"></i>
                    </el-tooltip>
                </el-menu-item>
                <el-menu-item
                    @click="toolsGroup('clean')"
                    class="pull-right">
                    <el-tooltip class="item" effect="dark" :content="$t('admin.public.clear')" placement="bottom">
                        <i class="fa fa-trash-o"></i>
                    </el-tooltip>
                </el-menu-item>
                <el-menu-item
                    @click="toolsGroup('language')"
                    class="pull-right">
                    <el-tooltip class="item" effect="dark" :content="$t('admin.public.switchLanguage')" placement="bottom">
                        <i class="fa fa-language"></i>
                    </el-tooltip>
                </el-menu-item>
            </el-menu>

            <!--Nav tabs-->
            <poemNavTabs/>

            <!--Pages-->
            <slot/>

            <!--SubViews-->
            <modAccount :value="modAccountFlag" :closeHandle="accountFlagHandle"/>
        </el-col>
    </el-row>
</template>

<script>
import helper from "../mixins/helper";
import theme from "../mixins/theme";
import navTab from "../mixins/navTab";
import modAccount from "../views/subViews/modAccount";

export default {

    name: 'admin',

    mixins: [helper, theme, navTab],

    components: {modAccount},

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
            modAccountFlag: false,
        }
    },

    created() {

        //定位menu active
        if (this.$store.state.isSignIn) {
            this.positionMenu();
        }

        //写入用户信息
        this.username = sessionStorage.getItem('username');

        //initCollapse
        let collapse = window.localStorage.getItem('collapse');
        collapse = collapse === 'true';
        this.isCollapse = collapse;
        window.localStorage.setItem('collapse', collapse);
    },

    updated() {

        //定位menu active
        if(this.pid !== this.activeIndex) {
            this.positionMenu();
        }
    },

    methods: {

        //工具组
        toolsGroup(type) {

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
                    this.accountFlagHandle();
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
                                this.tabClear();
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
                    this.tabClear();
                    this.switchLang();
                    break;
                }

                //菜单控制
                case 'menu': {
                    this.isCollapse = !this.isCollapse;
                    window.localStorage.setItem('collapse', this.isCollapse);
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
                    if (!onSelect) {
                        //刷新
                    } else {
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
            let active = (pid, id) => {
                this.menuHandleSelect(pid, false);
                this.pid = pid;
                this.id = id;
                this.activeIndex = pid;
                this.activeSubIndex = id;
            };

            for (let key in menuTree) {
                if (menuTree[key].child.length) {
                    for (let s_key in menuTree[key].child) {
                        if(menuTree[key].child[s_key].child.length) {
                            for (let ss_key in menuTree[key].child[s_key].child) {
                                if(this.$route.path === menuTree[key].child[s_key].child[ss_key].href) {
                                    active(
                                        menuTree[key].child[s_key].pid,
                                        menuTree[key].child[s_key].child[ss_key].pid + '-' + menuTree[key].child[s_key].child[ss_key].id,
                                    );
                                }
                            }
                        }else {
                            if (this.$route.path === menuTree[key].child[s_key].href) {
                                active(
                                    menuTree[key].child[s_key].pid,
                                    menuTree[key].child[s_key].id,
                                );
                            }
                        }
                    }
                }
            }
        },

        //切换页面
        openMenu(item) {
            this.$router.push(item.href);
        },

        //修改账号信息Flag互斥
        accountFlagHandle() {
            this.modAccountFlag = !this.modAccountFlag;
        },
    },

    watch: {

        '$store.state.menuTree.menuInfo'() {
            this.positionMenu();
        },

        isCollapse(v) {

            if(this.$store.state.isSignIn) {

                if(v) {
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
