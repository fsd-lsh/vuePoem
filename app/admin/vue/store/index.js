import Vue from 'vue';
import Vuex from 'vuex';

//挂载Vuex
Vue.use(Vuex);

//创建VueX对象
const store = new Vuex.Store({

    state: {},

    mutations: {

        //修改登录状态
        changeSignInState(state, bool) {
            state.isSignIn = bool;
        },

        //设置菜单树
        setMenuTree(state, bool) {
            state.menuTree = bool;
        },
    },

    getters:{

        //获取菜单树
        getMenuTree(state) {
            return state.menuTree;
        },

        //获取登录状态
        getSignIn(state) {
            return state.isSignIn;
        }
    },
});

export default store;
