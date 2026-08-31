<template>
    <div class="container">
        <b-row class="back-gray-2 wrapper">
            <div v-html="content"></div>
        </b-row>
    </div>
</template>
<script>
    import {mapFields} from "vuex-map-fields";

    export default {
        data() {
            return {
                content: ''
            }
        },
        mounted() {
            this.content = this.settings.about_us_content;
        },
        computed: {
            ...mapFields([
                'settings'
            ])
        }
    }
</script>
