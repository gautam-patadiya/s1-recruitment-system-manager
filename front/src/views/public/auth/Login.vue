<template>
    <div class="public-box-container">
        <div class="box pt-0 text-center">
            <h1 class="mb-0 text-white">Welcome to <strong>Login</strong></h1>
            <h4 class="text-white">Let's start your session.</h4>
        </div>

        <div class="public-box back-white mt-40">
            <h2>Enter Credentials</h2>
            <form @submit.prevent="submit" autocomplete="off">
                <div>
                    <a-form-item class="mb-10 required-input"
                     :validate-status="(formErrors.has('email') ? 'error' : '')"
                     :help="formErrors.first('email')">
                        <a-input placeholder="Email" v-model="formFields.email">
                            <a-icon  size="large" slot="prefix" type="user" style="color: rgba(0,0,0,.25)" />
                        </a-input>
                    </a-form-item>
                    <a-form-item class="mb-10 required-input"
                     :validate-status="(formErrors.has('password') ? 'error' : '')"
                     :help="formErrors.first('password')">
                        <a-input
                        type="password"
                        v-model="formFields.password"
                         placeholder="Password">
                            <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)" />
                        </a-input>
                    </a-form-item>
                    <a-form-item>
                        <a-row>
                            <a-col span="12">
                                <a-checkbox v-model="formFields.remember_me">
                                    Remember me
                                </a-checkbox>
                            </a-col>
                            <a-col span="12" class="text-right">
                                <div><router-link class="login-form-forgot" :to="{name: 'passwordForgot'}">Forgot password?</router-link></div>
                            </a-col>
                        </a-row>
                        <a-row>
                            <a-col span="24" class="mt-20 text-center">
                                <a-button size="large" type="primary" block html-type="submit" class="login-form-button">
                                    <span class="pl-10 pr-10">Log in</span>
                                </a-button>
                            </a-col>
                            <a-col span="24" class="mt-20 text-right">
                                <div><router-link class="login-form-forgot" :to="{name: 'register'}">Create an Account</router-link></div>
                            </a-col>
                        </a-row>
                    </a-form-item>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
    import Error from "../../../util/Error";
    import {request} from "../../../util/request";
    import {getAuthUser, handleServerError, hasAuthUser, refresh, removeStorage, setStorage} from "../../../util/utils";
    import {loginFailed, loginSuccess} from "../../../util/notify";

    const DEFAULT_FORM_STATE = {
        email: null,
        password: null,
        remember_me: false,
        _method: 'post',
    };

    export default {
        data() {
            return {
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({}),
                isSubmitted: false
            }
        },
        mounted() {
            this.formFields = {...DEFAULT_FORM_STATE};

            if(hasAuthUser()) {
                const user = getAuthUser();
                if(user.type == 1) {
                    return this.$router.push({name: 'home'});
                }

                this.$router.push({name: 'landing'});
            }
        },
        methods: {
            submit() {
                if(this.isSubmitted)
                    return false;

                this.isSubmitted = true;
                this.formErrors = new Error({});
                removeStorage('auth');

                request({
                    method: 'POST',
                    url: `/auth/sign/in`,
                    data: this.formFields,
                })
                .then((response) => {
                    if(response.data) {
                        const {access_token} = response.data;
                        if(access_token) {
                            setStorage('auth', JSON.stringify(response.data));
                            loginSuccess();
                            setTimeout(() => refresh(), 2000);
                            return;
                        }
                    }

                    removeStorage('auth');
                    loginFailed();
                })
                .catch((errors) => {
                    if(errors.status && errors.status === 422) {
                        this.formErrors = new Error(errors.data.errors)
                    }

                    handleServerError(errors);
                })
                .finally(() => {
                    this.isSubmitted = false;
                });
            }
        }
    }
</script>
