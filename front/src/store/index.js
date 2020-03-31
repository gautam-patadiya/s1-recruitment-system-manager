import mutations from './mutations';
import actions from './actions';
import getters from './getters';
import Vuex from "vuex";
import Vue from "vue";
import {getStorage, hasAuthUser, getAuthUser} from "../util/utils";
Vue.use(Vuex);

function store(data) {
    return new Vuex.Store({
        state: {
            sidebarCollapsed: (JSON.parse(getStorage('sidebar')) === true),
            hasAuth: hasAuthUser(),
            authUser: (hasAuthUser() ? getAuthUser() : {}),
            pendingRequests: 0,
            uploads: 0,
            settings: data
        },
        namespaced: true,
        getters,
        mutations,
        actions
    });
}

export default store;
