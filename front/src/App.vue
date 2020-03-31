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
    const DEFAULT_LAYOUT = 'private';

    export default {
        computed: {
            ...mapFields([
                'pendingRequests'
            ]),
            layout() {
                return (this.$route.meta.layout || DEFAULT_LAYOUT) + '-layout';
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
