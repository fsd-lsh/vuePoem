import { createStore } from "vuex";

window.addEventListener('storage', (e) => {
    sessionStorage.setItem(e.key, e.oldValue);
});

export default createStore({
    state: {},

    mutations: {

        changeSignInState(state, bool) {
            state.isSignIn = bool;
        },

        setMenuTree(state, bool) {
            state.menuTree = bool;
        },
    },

    getters:{

        getMenuTree(state) {
            return state.menuTree;
        },

        getSignIn(state) {
            return state.isSignIn;
        }
    },
});
