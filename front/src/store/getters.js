import { getField } from "vuex-map-fields";

export default {
    getField,

    getPendingRequest(state) {
        return state.pendingRequests;
    },

    getUploads(state) {
        return state.uploads;
    },
}
