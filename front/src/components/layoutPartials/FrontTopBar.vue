<template>
    <div id="front-wrapper">
        <div class="container">
            <div>
                <div class="nav">
                    <input type="checkbox" id="nav-check">
                    <div class="nav-header">
                        <div class="nav-title">
                            <router-link :to="{name: 'landing'}">
                                {{textLogoPartOne}}{{textLogoPartTwo}}
                            </router-link>
                        </div>
                    </div>
                    <div class="nav-btn">
                        <label for="nav-check">
                            <span></span>
                            <span></span>
                            <span></span>
                        </label>
                    </div>

                    <div class="nav-links">
                        <router-link :to="{name: 'profile'}" v-if="auth">{{user.first_name}} {{user.last_name[0]}}.</router-link>
                        <router-link :to="{name: 'login'}" v-if="!auth">Login</router-link>
                        <router-link :to="{name: 'aboutUs'}">About us</router-link>
                        <a href="javascript:;" @click="logout" v-if="auth">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        },
    }
</script>
<style lang="scss">

</style>
