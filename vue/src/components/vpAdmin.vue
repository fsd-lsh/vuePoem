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
                background-color="var(--menu-background-color)"
                text-color="var(--menu-font-normal-color)"
                :unique-opened="true"
                :collapse-transition="true"
                :collapse="isCollapse">
                <div
                    v-for="(item, key) in this.subMenuTree.child"
                    :key="key">

                    <!--多级-->
                    <el-sub-menu
                        v-if="item.child.length"
                        :show-timeout="200"
                        :hide-timeout="200"
                        :index="item.id">
                        <template
                            #title>
                            <i :class="item.icon"></i>
                            <span>&nbsp;&nbsp;&nbsp;{{ item.title }}</span>
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
                    </el-sub-menu>

                    <!--单级-->
                    <el-menu-item
                        @click="openMenu(item)"
                        v-if="!item.child.length"
                        :key="key"
                        :index="item.id">
                        <i :class="item.icon"></i>
                        <span>&nbsp;&nbsp;&nbsp;{{ item.title }}</span>
                    </el-menu-item>
                </div>
            </el-menu>

            <!--Config panel-->
            <vp-config :drawer="drawer" :close-handle="configFlagHandle"/>
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
                background-color="var(--title-color)"
                text-color="#fff"
                style="width:100%!important;"
                active-text-color="var(--main-color)">
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
                    v-for="item in this.$store.state.menuTree.menuInfo"
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
                <el-sub-menu
                    index="2"
                    class="pull-right">
                    <el-menu-item
                        @click="toolsGroup('modAccount')"
                        index="2-1">{{$t('admin.public.modAccount')}}
                    </el-menu-item>
                    <el-menu-item
                        @click="toolsGroup('signOut')"
                        index="2-2">{{$t('admin.public.signOut')}}
                    </el-menu-item>
                </el-sub-menu>
                <el-menu-item
                    @click="toolsGroup('fullScreen')"
                    class="pull-right">
                    <el-tooltip v-if="!iFullscreen" class="item" effect="dark" :content="$t('admin.public.fullScreen')" placement="bottom">
                        <i class="fa fa-arrows-alt"></i>
                    </el-tooltip>
                    <el-tooltip v-if="iFullscreen" class="item" effect="dark" :content="$t('admin.public.exitFullScreen')" placement="bottom">
                        <i class="fa fa-compress"></i>
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
            <vp-nav-tabs/>

            <!--Pages-->
            <slot/>

            <!--SubViews-->
            <mod-account :value="modAccountFlag" :closeHandle="accountFlagHandle"/>
        </el-col>
    </el-row>
</template>

<script>
import helper from "../mixins/helper";
import navTab from "../mixins/navTab";
import modAccount from "../views/subViews/modAccount.vue";

export default {

    name: 'vpAdmin',

    mixins: [helper, navTab],

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
                    this.configFlagHandle();
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

        openMenu(item) {
            this.$router.push(item.href);
        },

        accountFlagHandle() {
            this.modAccountFlag = !this.modAccountFlag;
        },

        configFlagHandle() {
            this.drawer = !this.drawer
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
    #top-nav-group {
        justify-content: flex-end;
        flex-grow: 1;
    }
</style>
