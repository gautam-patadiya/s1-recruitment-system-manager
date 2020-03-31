<template>
    <a-layout id="private-wrapper">
        <private-sidebar></private-sidebar>
        <a-layout id="private-content-wrapper">
            <div id="border-container" class="mt-5">
                <private-header></private-header>
                <a-layout-content id="private-content">
                    <slot></slot>
                </a-layout-content>
            </div>
        </a-layout>
    </a-layout>
</template>
<script>
    import PrivateSidebar from "../components/layoutPartials/PrivateSidebar";
    import PrivateHeader from "../components/layoutPartials/PrivateHeader";

    export default {
        components: {
            PrivateSidebar,
            PrivateHeader,
        },
        data() {
            return {
                collapsed: false,
            };
        }
    }
</script>