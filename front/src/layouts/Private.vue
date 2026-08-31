<template>
    <div id="private-wrapper" :class="{ 'sidebar-is-collapsed': sidebarCollapsed }">
        <private-sidebar v-if="!sidebarCollapsed"></private-sidebar>

        <main id="private-content-wrapper">
            <div id="border-container">
                <private-header></private-header>
                <section id="private-content">
                    <slot></slot>
                </section>
            </div>
        </main>
    </div>
</template>

<script>
    import {mapFields} from "vuex-map-fields";
    import PrivateSidebar from "../components/layoutPartials/PrivateSidebar";
    import PrivateHeader from "../components/layoutPartials/PrivateHeader";

    export default {
        components: {
            PrivateSidebar,
            PrivateHeader,
        },
        computed: {
            ...mapFields([
                'sidebarCollapsed'
            ]),
        },
    }
</script>
