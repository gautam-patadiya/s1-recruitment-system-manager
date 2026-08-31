<template>
    <div class="auth-page">
        <b-row class="auth-shell no-gutters">
            <b-col cols="12" lg="4" class="auth-left-panel">
                <div class="auth-brand">
                    <span>ATS</span>
                    <strong>Recruitment Manager</strong>
                </div>
                <h1>Welcome Back</h1>
                <p>Login to manage jobs, candidates, interviews, and reports from one clean workspace.</p>
                <div class="auth-feature-list">
                    <span><i class="bi bi-check-circle mr-5"></i>Track applications</span>
                    <span><i class="bi bi-check-circle mr-5"></i>Manage interviews</span>
                    <span><i class="bi bi-check-circle mr-5"></i>Review hiring reports</span>
                </div>
            </b-col>

            <b-col cols="12" lg="8" class="auth-form-panel">
                <div class="auth-form-card">
                    <div class="auth-form-header">
                        <div class="auth-card-brand">
                            <span>ATS</span>
                            <strong>Recruitment Manager</strong>
                        </div>
                        <h2>Login</h2>
                        <p>Enter your email and password to continue.</p>
                    </div>
            <form @submit.prevent="submit" autocomplete="off">
                <div>
                    <b-form-group
                        label="Email *"
                        class="mb-15 required-input"
                        :state="formErrors.has('email') ? false : null"
                        :invalid-feedback="formErrors.first('email')"
                    >
                        <b-form-input
                            placeholder="admin@example.com"
                            v-model="formFields.email"
                            :state="formErrors.has('email') ? false : null"
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group
                        label="Password *"
                        class="mb-15 required-input"
                        :state="formErrors.has('password') ? false : null"
                        :invalid-feedback="formErrors.first('password')"
                    >
                        <b-form-input
                            type="password"
                            v-model="formFields.password"
                            placeholder="Enter password"
                            :state="formErrors.has('password') ? false : null"
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <div class="auth-form-options">
                            <b-form-checkbox v-model="formFields.remember_me">
                                Remember me
                            </b-form-checkbox>
                            <router-link class="auth-link" :to="{name: 'passwordForgot'}">Forgot password?</router-link>
                        </div>
                        <b-button size="lg" variant="primary" block type="submit" class="auth-submit-button" :disabled="isSubmitted">
                            {{ isSubmitted ? 'Logging in...' : 'Login' }}
                        </b-button>
                        <p class="auth-bottom-text">
                            New here?
                            <router-link class="auth-link" :to="{name: 'register'}">Create an account</router-link>
                        </p>
                    </b-form-group>
                </div>
            </form>
                </div>
            </b-col>
        </b-row>
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
