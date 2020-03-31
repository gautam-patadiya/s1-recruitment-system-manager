<template>
    <a-layout-header id="private-content-header">
        <a-row >
            <a-col span="12">
                <a-icon
                    class="trigger"
                    :type="sidebarCollapsed ? 'menu-unfold' : 'menu-fold'"
                    @click="handleCollapseClick"
                />
            </a-col>
            <a-col span="12" style="text-align: right;">
                <router-link :to="{name: 'privateProfile'}" title="Profile">
                    <span class="nav-text text-capitalize">
                        {{authUser.first_name}} {{(authUser.last_name) ? authUser.last_name[0]+'.' : ''}}
                    </span>
                </router-link>
                <a href="javascript:;" @click="logout()" title="Logout">
                    <a-icon type="logout" />
                </a>
            </a-col>
        </a-row>
    </a-layout-header>
</template>
<script>
    import { mapFields } from 'vuex-map-fields';
    import {refresh, removeStorage} from "../../util/utils";

    export default {
        data() {
            return {

            }
        },
        methods: {
            handleCollapseClick() {
                this.$store.dispatch('setSidebarCollapse', !this.sidebarCollapsed);
            },
            logout() {
                removeStorage('auth');
                refresh();
            }
        },
        computed: {
            ...mapFields([
                'sidebarCollapsed',
                'authUser',
            ])
        }
    }
</script>
