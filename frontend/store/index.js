import Vue from 'vue';
import Vuex from 'vuex';

//挂载Vuex
Vue.use(Vuex)

//创建VueX对象
const store = new Vuex.Store({

    state: {

        //登录状态
        isSignIn: false,
    }
})

export default store;
