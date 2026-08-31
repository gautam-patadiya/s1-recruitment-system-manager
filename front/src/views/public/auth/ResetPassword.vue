<template>
    <div class="auth-page">
        <b-row class="auth-shell no-gutters">
            <b-col cols="12" lg="4" class="auth-left-panel">
                <div class="auth-brand">
                    <span>ATS</span>
                    <strong>Recruitment Manager</strong>
                </div>
                <h1>Reset Password</h1>
                <p>Create a new password to get back into your recruitment workspace.</p>
                <div class="auth-feature-list">
                    <span><i class="bi bi-check-circle mr-5"></i>Use a strong password</span>
                    <span><i class="bi bi-check-circle mr-5"></i>Confirm before saving</span>
                    <span><i class="bi bi-check-circle mr-5"></i>Login after reset</span>
                </div>
            </b-col>

            <b-col cols="12" lg="8" class="auth-form-panel">
                <div class="auth-form-card">
                    <div class="auth-form-header">
                        <div class="auth-card-brand">
                            <span>ATS</span>
                            <strong>Recruitment Manager</strong>
                        </div>
                        <h2>Set New Password</h2>
                        <p>Enter your email and new password details.</p>
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
                    <b-form-group
                        label="Password *"
                        class="mb-15 required-input"
                        :state="formErrors.has('password') ? false : null"
                        :invalid-feedback="formErrors.first('password')"
                    >
                        <b-form-input
                            placeholder="Password"
                            v-model="formFields.password"
                            type="password"
                            :state="formErrors.has('password') ? false : null"
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group
                        label="Confirm Password *"
                        class="mb-15 required-input"
                        :state="formErrors.has('password_confirmation') ? false : null"
                        :invalid-feedback="formErrors.first('password_confirmation')"
                    >
                        <b-form-input
                            placeholder="Confirm Password"
                            v-model="formFields.password_confirmation"
                            type="password"
                            :state="formErrors.has('password_confirmation') ? false : null"
                        ></b-form-input>
                    </b-form-group>
                    <b-button size="lg" variant="primary" block type="submit" class="auth-submit-button" :disabled="isSubmitted">
                        {{ isSubmitted ? 'Updating...' : 'Reset Password' }}
                    </b-button>
                    <p class="auth-bottom-text">
                        Already reset?
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
    import {handleServerError, hasAuthUser, removeStorage} from "../../../util/utils";
    import {
        resetPasswordSuccess,
    } from "../../../util/notify";

    const DEFAULT_FORM_STATE = {
        email: null,
        password: null,
        password_confirmation: null,
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
                    url: `/auth/password/reset`,
                    data: {...this.formFields, token: this.$route.params.token},
                })
                .then((response) => {
                    this.formFields = {...DEFAULT_FORM_STATE};
                    resetPasswordSuccess();
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
