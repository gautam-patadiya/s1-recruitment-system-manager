<template>
    <header id="private-content-header">
        <button class="sidebar-toggle" type="button" @click="handleCollapseClick">
            {{ sidebarCollapsed ? 'Open Menu' : 'Close Menu' }}
        </button>

        <div class="header-actions">
            <router-link :to="{name: 'privateProfile'}" class="profile-link" title="Profile">
                {{authUser.first_name}} {{(authUser.last_name) ? authUser.last_name[0]+'.' : ''}}
            </router-link>

            <button class="logout-button" type="button" @click="logout()" title="Logout">
                <b-icon icon="box-arrow-right"></b-icon>
            </button>
        </div>
    </header>
</template>

<script>
    import { mapFields } from 'vuex-map-fields';
    import {refresh, removeStorage} from "../../util/utils";

    export default {
        data() {
            return {};
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
