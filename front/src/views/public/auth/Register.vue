<template>
    <div class="auth-page">
        <b-row class="auth-shell no-gutters">
            <b-col cols="12" lg="4" class="auth-left-panel">
                <div class="auth-brand">
                    <span>ATS</span>
                    <strong>Recruitment Manager</strong>
                </div>
                <h1>Create Account</h1>
                <p>Join the recruitment portal and manage your applications in one simple place.</p>
                <div class="auth-feature-list">
                    <span><i class="bi bi-check-circle mr-5"></i>Create your candidate profile</span>
                    <span><i class="bi bi-check-circle mr-5"></i>Upload documents</span>
                    <span><i class="bi bi-check-circle mr-5"></i>Apply for jobs</span>
                </div>
            </b-col>

            <b-col cols="12" lg="8" class="auth-form-panel">
                <div class="auth-form-card auth-form-card-wide">
                    <div class="auth-form-header">
                        <div class="auth-card-brand">
                            <span>ATS</span>
                            <strong>Recruitment Manager</strong>
                        </div>
                        <h2>Create Account</h2>
                        <p>Fill in your details to register.</p>
                    </div>

                    <form @submit.prevent="submit" autocomplete="off">
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group label="First Name *" class="mb-15 required-input" :state="formErrors.has('first_name') ? false : null" :invalid-feedback="formErrors.first('first_name')">
                                    <b-form-input placeholder="First Name" v-model="formFields.first_name" :state="formErrors.has('first_name') ? false : null"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Last Name *" class="mb-15 required-input" :state="formErrors.has('last_name') ? false : null" :invalid-feedback="formErrors.first('last_name')">
                                    <b-form-input placeholder="Last Name" v-model="formFields.last_name" :state="formErrors.has('last_name') ? false : null"></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>

                        <b-form-group label="Email *" class="mb-15 required-input" :state="formErrors.has('email') ? false : null" :invalid-feedback="formErrors.first('email')">
                            <b-form-input placeholder="candidate@example.com" v-model="formFields.email" :state="formErrors.has('email') ? false : null"></b-form-input>
                        </b-form-group>

                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group label="Password *" class="mb-15 required-input" :state="formErrors.has('password') ? false : null" :invalid-feedback="formErrors.first('password')">
                                    <b-form-input type="password" v-model="formFields.password" placeholder="Password" :state="formErrors.has('password') ? false : null"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Confirm Password *" class="mb-15 required-input" :state="formErrors.has('password_confirmation') ? false : null" :invalid-feedback="formErrors.first('password_confirmation')">
                                    <b-form-input type="password" v-model="formFields.password_confirmation" placeholder="Confirm Password" :state="formErrors.has('password_confirmation') ? false : null"></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group label="Gender *" class="mb-15 required-input" :state="formErrors.has('gender') ? false : null" :invalid-feedback="formErrors.first('gender')">
                                    <v-select v-model="formFields.gender" :options="genderOptions" label="text" :reduce="option => option.value" :class="{ 'is-invalid': formErrors.has('gender') }" placeholder="Pick Gender"></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Birthday *" class="mb-15 required-input" :state="formErrors.has('birthday') ? false : null" :invalid-feedback="formErrors.first('birthday')">
                                    <date-picker class="auth-date-picker" v-model="formFields.birthday" format="DD-MM-YYYY" placeholder="Pick Birth date" :input-class="formErrors.has('birthday') ? 'form-control is-invalid' : 'form-control'"></date-picker>
                                </b-form-group>
                            </b-col>
                        </b-row>

                        <b-button size="lg" variant="primary" block type="submit" class="auth-submit-button" :disabled="isSubmitted">
                            {{ isSubmitted ? 'Creating account...' : 'Create Account' }}
                        </b-button>
                        <p class="auth-bottom-text">
                            Already have an account?
                            <router-link class="auth-link" :to="{name: 'login'}">Login</router-link>
                        </p>
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
    import {registerFailed, registerSuccess} from "../../../util/notify";
    import moment from "moment";

    const DEFAULT_FORM_STATE = {
        first_name: null,
        last_name: null,
        email: null,
        password: null,
        gender: undefined,
        birthday: null,
        password_confirmation: null,
        remember_me: false,
        _method: 'post',
    };

    export default {
        data() {
            return {
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({}),
                isSubmitted: false,
                genderOptions: [
                    {value: '1', text: 'Male'},
                    {value: '2', text: 'Female'},
                ],
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
                    url: `/auth/sign/up`,
                    data: {
                        ...this.formFields,
                        birthday: ((this.formFields.birthday) ? moment(this.formFields.birthday).format('YYYY-MM-DD') : null),
                    },
                })
                .then((response) => {
                    this.formFields = {...DEFAULT_FORM_STATE};
                    registerSuccess();
                })
                .catch((errors) => {
                    if (errors.status && errors.status === 422) {
                        this.formErrors = new Error(errors.data.errors)
                    } else {
                        registerFailed();
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
