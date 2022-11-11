import { createStore } from "vuex";

window.addEventListener('storage', (e) => {
    sessionStorage.setItem(e.key, e.oldValue);
});

export default createStore({

    state: {
        isSignIn: false,
        menuTree: [],
    },

    mutations: {

        changeSignInState(state, bool) {
            state.isSignIn = bool;
        },

        setMenuTree(state, v) {
            state.menuTree = v;
        },
    },

    getters:{

        getSignIn(state) {
            return state.isSignIn;
        },

        getMenuTree(state) {
            return state.menuTree;
        },
    },
});
