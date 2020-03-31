export default {
    setSettings(context, payload) {
        context.commit('setSettings', payload);
    },

    setSidebarCollapse(context, payload) {
        context.commit('setSidebarCollapse', payload);
    },

    addPendingRequests(context, payload) {
        context.commit('addPendingRequests', payload);
    },

    removePendingRequests(context, payload) {
        context.commit('removePendingRequests', payload);
    },

    refreshUploads(context, payload) {
        context.commit('refreshUploads', payload);
    }
}
