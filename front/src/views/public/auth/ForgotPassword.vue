<template>
    <div class="public-box-container">
        <div class="box pt-0 text-center">
            <h1 class="mb-0 text-white">Forgot Password <strong>Request</strong></h1>
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
                    <a-form-item>
                        <a-row>
                            <a-col span="12" class="mt-10">
                                <a-button size="large" type="primary" html-type="submit" class="login-form-button">
                                    <span class="pl-10 pr-10">Submit</span>
                                </a-button>
                            </a-col>
                            <a-col span="12" class="text-right mt-10">
                                <router-link class="login-form-forgot" :to="{name: 'login'}">Back to Login</router-link>
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
