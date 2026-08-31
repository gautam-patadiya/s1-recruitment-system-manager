<template>
    <header class="public-topbar">
        <div class="container">
            <div class="public-topbar-inner">
                <router-link :to="{name: 'landing'}" class="public-brand-link">
                    <span>{{ logoInitials }}</span>
                    <strong>{{textLogoPartOne}}{{textLogoPartTwo}}</strong>
                </router-link>

                <div class="public-nav-links">
                    <router-link :to="{name: 'landing'}">Jobs</router-link>
                    <router-link :to="{name: 'aboutUs'}">About us</router-link>
                    <router-link :to="{name: 'profile'}" v-if="auth" class="public-user-link">
                        <i class="bi bi-person-circle mr-5"></i>
                        {{userName}}
                    </router-link>
                    <router-link :to="{name: 'login'}" v-if="!auth" class="public-login-link">Login</router-link>
                    <button type="button" class="public-logout-button" @click="logout" v-if="auth">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </header>
</template>
<script>
    import {getAuthUser, hasAuthUser, refresh, removeStorage} from "../../util/utils";
    import {mapFields} from "vuex-map-fields";

    export default {
        data() {
            return {
                auth: false,
                user: {},
                textLogoPartOne: '',
                textLogoPartTwo: '',
            };
        },
        mounted() {
            if(hasAuthUser()) {
                this.auth = true;
                this.user = getAuthUser();
            }

            this.textLogoPartOne = this.settings.text_logo_part_one;
            this.textLogoPartTwo = this.settings.text_logo_part_two;
        },
        methods: {
            logout() {
                removeStorage('auth');
                refresh();
            }
        },
        computed: {
            ...mapFields([
                'settings'
            ]),
            logoInitials() {
                const firstLetter = this.textLogoPartOne ? this.textLogoPartOne[0] : 'A';
                const secondLetter = this.textLogoPartTwo ? this.textLogoPartTwo[0] : 'T';

                return firstLetter + secondLetter;
            },
            userName() {
                const firstName = this.user.first_name || '';
                const lastName = this.user.last_name || '';
                const lastInitial = lastName ? `${lastName[0]}.` : '';

                return `${firstName} ${lastInitial}`.trim();
            },
        },
    }
</script>
<style lang="scss">

</style>
