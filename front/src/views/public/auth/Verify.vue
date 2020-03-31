<template>
    <div class="public-box-container">
        <div class="box pt-0 text-center">
            <h1 class="mb-0 text-white">Email <strong>Verification</strong></h1>
        </div>
        <div class="public-box back-white mt-40">
            <div class="text-center mb-40">
                <a-icon v-if="isSubmitted" type="loading" class="loading-icon" />
                <a-icon v-if="status === 1" type="check-circle" class="loading-icon success" />
                <a-icon v-if="status === 0" type="close-circle" class="loading-icon danger" />
            </div>

            <a-row>
                <a-col span="24" class="text-right mt-10">
                    <router-link class="login-form-forgot" :to="{name: 'login'}">Login</router-link>
                </a-col>
            </a-row>
        </div>
    </div>
</template>
<script>
    import Error from "../../../util/Error";
    import {request} from "../../../util/request";
    import {handleServerError, removeStorage} from "../../../util/utils";
    import {verifyEmailFailed, verifyEmailSuccess,} from "../../../util/notify";

    const DEFAULT_FORM_STATE = {
        _method: 'post',
    };

    export default {
        data() {
            return {
                formFields: {...DEFAULT_FORM_STATE},
                isSubmitted: false,
                status: null,
            }
        },
        mounted() {
            this.submit();
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
                    url: `/auth/email/verify`,
                    data: {
                        ...this.formFields,
                        token: this.$route.params.token
                    },
                })
                .then((response) => {
                    verifyEmailSuccess();
                    this.status = 1;
                })
                .catch((errors) => {
                    if(errors.status && errors.status === 422) {
                        verifyEmailFailed();
                        this.status = 0;
                    }

                    handleServerError(errors);
                }).
                finally(() => {
                    this.isSubmitted = false;
                })
            },
        }
    }
</script>
<style lang="scss">
    .loading-icon{
        font-size: 86px;
        color: #737C86;
    }

    .success{
        color: #3cce77;
    }

    .danger{
        color: #ce3c3c;
    }
</style>
