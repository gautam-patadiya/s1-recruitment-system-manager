<template>
    <div id="front-wrapper">
        <front-top-bar></front-top-bar>
        <slot></slot>
    </div>
</template>
<script>
    import FrontTopBar from "../components/layoutPartials/FrontTopBar";

    export default {
        components: {
            FrontTopBar
        },
        data() {
            return {

            };
        },
        mounted() {

        },
        methods: {

        }
    }
</script>
<style lang="scss">

</style>
