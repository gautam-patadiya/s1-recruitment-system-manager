import {updateField} from "vuex-map-fields";
import {setStorage} from "../util/utils";

export default {
    updateField,

    setSidebarCollapse(state, payload) {
        setStorage('sidebar', payload);
        state.sidebarCollapsed = payload;
    },

    setSettings(state, payload) {
        state.settings = payload;
    },

    addPendingRequests(state, payload) {
        state.pendingRequests = state.pendingRequests + 1;
    },

    removePendingRequests(state, payload) {
        state.pendingRequests = (state.pendingRequests > 0) ? state.pendingRequests - 1 : 0;
    },

    refreshUploads(state, payload) {
        state.uploads = state.uploads + 1;
    }
}
