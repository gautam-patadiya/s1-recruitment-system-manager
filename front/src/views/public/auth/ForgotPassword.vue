<template>
    <div class="auth-page">
        <b-row class="auth-shell no-gutters">
            <b-col cols="12" lg="4" class="auth-left-panel">
                <div class="auth-brand">
                    <span>ATS</span>
                    <strong>Recruitment Manager</strong>
                </div>
                <h1>Forgot Password?</h1>
                <p>No problem. Enter your email and we will send password reset instructions.</p>
                <div class="auth-feature-list">
                    <span><i class="bi bi-check-circle mr-5"></i>Secure reset link</span>
                    <span><i class="bi bi-check-circle mr-5"></i>Quick email recovery</span>
                    <span><i class="bi bi-check-circle mr-5"></i>Back to login anytime</span>
                </div>
            </b-col>

            <b-col cols="12" lg="8" class="auth-form-panel">
                <div class="auth-form-card">
                    <div class="auth-form-header">
                        <div class="auth-card-brand">
                            <span>ATS</span>
                            <strong>Recruitment Manager</strong>
                        </div>
                        <h2>Reset Request</h2>
                        <p>Enter the email address connected with your account.</p>
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
                            placeholder="you@example.com"
                            v-model="formFields.email"
                            :state="formErrors.has('email') ? false : null"
                        ></b-form-input>
                    </b-form-group>
                    <b-button size="lg" variant="primary" block type="submit" class="auth-submit-button" :disabled="isSubmitted">
                        {{ isSubmitted ? 'Sending...' : 'Send Reset Link' }}
                    </b-button>
                    <p class="auth-bottom-text">
                        Remember password?
                        <router-link class="auth-link" :to="{name: 'login'}">Back to Login</router-link>
                    </p>
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
    import {handleServerError, hasAuthUser, refresh, removeStorage, setStorage} from "../../../util/utils";
    import {forgotPasswordSuccess, loginFailed, loginSuccess, serverError} from "../../../util/notify";

    const DEFAULT_FORM_STATE = {
        email: null,
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
                this.$router.push({name: 'home'})
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
                    url: `/auth/password/forgot`,
                    data: this.formFields,
                })
                .then((response) => {
                    this.formFields = {...DEFAULT_FORM_STATE};
                    forgotPasswordSuccess();
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
