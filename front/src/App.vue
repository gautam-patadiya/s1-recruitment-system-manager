<template>
    <div id="app">
        <vue-progress-bar></vue-progress-bar>
        <component :is="layout">
            <router-view/>
        </component>
    </div>
</template>
<script>
    import {mapFields} from 'vuex-map-fields';
    import toastBus from "./util/toastBus";

    const DEFAULT_LAYOUT = 'private';

    export default {
        mounted() {
            toastBus.$on('show-toast', this.showToast);
        },
        beforeDestroy() {
            toastBus.$off('show-toast', this.showToast);
        },
        computed: {
            ...mapFields([
                'pendingRequests'
            ]),
            layout() {
                return (this.$route.meta.layout || DEFAULT_LAYOUT) + '-layout';
            }
        },
        methods: {
            showToast(toast) {
                const hasMessage = toast.message && toast.message !== toast.title;

                this.$bvToast.toast(hasMessage ? toast.message : toast.title, {
                    title: hasMessage ? toast.title : this.toastTitle(toast.variant),
                    variant: toast.variant,
                    solid: true,
                    autoHideDelay: 3500,
                    appendToast: true,
                    toaster: 'b-toaster-top-right',
                });
            },
            toastTitle(variant) {
                if (variant === 'success') {
                    return 'Success';
                }

                if (variant === 'warning') {
                    return 'Warning';
                }

                if (variant === 'danger') {
                    return 'Error';
                }

                return 'Update';
            }
        },
        watch: {
            pendingRequests: {
                immediate: true,
                handler(newVal) {
                    if(newVal <= 0) {
                        this.$Progress.finish();
                    }

                    if(newVal > 0) {
                        this.$Progress.start();
                    }
                }
            }
        }
    }
</script>
<style lang="scss">
    @import "assets/theme.scss";
</style>
