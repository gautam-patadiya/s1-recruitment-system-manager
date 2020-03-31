<template>
    <div class="public-box-container">
        <div class="box pt-0 text-center">
            <h1 class="mb-0 text-white">Welcome to <strong>Registrations</strong></h1>
            <h4 class="text-white">Let's create your account</h4>
        </div>

        <div class="public-box back-white mt-40">
            <h2>Enter Details</h2>
            <form @submit.prevent="submit" autocomplete="off">
                <div>
                    <a-form-item class="mb-10 required-input"
                     :validate-status="(formErrors.has('first_name') ? 'error' : '')"
                     :help="formErrors.first('first_name')">
                        <a-input placeholder="First Name" v-model="formFields.first_name">
                            <a-icon  size="large" slot="prefix" type="user" style="color: rgba(0,0,0,.25)" />
                        </a-input>
                    </a-form-item>
                    <a-form-item class="mb-10 required-input"
                     :validate-status="(formErrors.has('last_name') ? 'error' : '')"
                     :help="formErrors.first('last_name')">
                        <a-input placeholder="Last Name" v-model="formFields.last_name">
                            <a-icon  size="large" slot="prefix" type="user" style="color: rgba(0,0,0,.25)" />
                        </a-input>
                    </a-form-item>
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
                    <a-form-item class="mb-10 required-input"
                     :validate-status="(formErrors.has('password_confirmation') ? 'error' : '')"
                     :help="formErrors.first('password_confirmation')">
                        <a-input
                        type="password"
                        v-model="formFields.password_confirmation"
                         placeholder="Confirm Password">
                            <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)" />
                        </a-input>
                    </a-form-item>
                    <a-form-item class="mb-10 required-input"
                         :validate-status="(formErrors.has('gender') ? 'error' : '')"
                         :help="formErrors.first('gender')">
                        <a-select allowClear v-model="formFields.gender" placeholder="Pick Gender" >
                            <a-select-option value="1">Male</a-select-option>
                            <a-select-option value="2">Female</a-select-option>
                        </a-select>
                    </a-form-item>
                    <a-form-item class="mb-10 required-input"
                                 :validate-status="(formErrors.has('birthday') ? 'error' : '')"
                                 :help="formErrors.first('birthday')">
                        <a-date-picker v-model="formFields.birthday" format="DD-MM-YYYY" placeholder="Pick Birth date"/>
                    </a-form-item>
                    <a-form-item>
                        <a-row>
                            <a-col span="24" class="mt-20 text-center">
                                <a-button size="large" type="primary" block html-type="submit" class="login-form-button">
                                    <span class="pl-10 pr-10">Register</span>
                                </a-button>
                            </a-col>
                            <a-col span="24" class="mt-20 text-right">
                                <div><router-link class="login-form-forgot" :to="{name: 'login'}">Let me Login</router-link></div>
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
