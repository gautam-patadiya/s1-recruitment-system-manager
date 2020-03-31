<template>
    <form @submit.prevent="handleFormSubmit" autocomplete="off">
        <div>
            <a-col :md="24">
                <h1>Profile</h1>
            </a-col>
            <a-col :xs="24" :sm="24" :md="24" class="mb-15">
                <a-card size="small" :title="null" class="custom-card">
                    <div class="body">
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="First Name" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('first_name') ? 'error' : '')"
                                     :help="formErrors.first('first_name')">
                                    <a-input v-model="formFields.first_name" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Last Name" class="mb-10 required-input"
                                             :validate-status="(formErrors.has('last_name') ? 'error' : '')"
                                             :help="formErrors.first('last_name')">
                                    <a-input v-model="formFields.last_name" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Password" class="mb-10"
                                             :validate-status="(formErrors.has('password') ? 'error' : '')"
                                             :help="formErrors.first('password')">
                                    <a-input type="password" v-model="formFields.password" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Password Confirm" class="mb-10"
                                             :validate-status="(formErrors.has('password_confirmation') ? 'error' : '')"
                                             :help="formErrors.first('password_confirmation')">
                                    <a-input type="password" v-model="formFields.password_confirmation" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Email" class="mb-10 required-input"
                                             :validate-status="(formErrors.has('email') ? 'error' : '')"
                                             :help="formErrors.first('email')">
                                    <a-input v-model="formFields.email" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Gender" class="mb-10 required-input"
                                             :validate-status="(formErrors.has('gender') ? 'error' : '')"
                                             :help="formErrors.first('gender')">
                                    <a-select allowClear v-model="formFields.gender">
                                        <a-select-option value="1">Male</a-select-option>
                                        <a-select-option value="2">Female</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Birthday" class="mb-10 required-input"
                                             :validate-status="(formErrors.has('birthday') ? 'error' : '')"
                                             :help="formErrors.first('birthday')">
                                    <a-date-picker v-model="formFields.birthday" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                        </a-row>
                    </div>
                    <span class="apply-button mt-10">
                        <a-row>
                            <a-col :sm="8" class="text-left">
                                <a @click="handleFormSubmit">Update</a>
                            </a-col>
                        </a-row>
                    </span>
                </a-card>
            </a-col>
        </div>
    </form>
</template>
<script>
    import Error from "../../util/Error";
    import {getAuthUser, handleServerError, refresh, removeStorage, setStorage} from "../../util/utils";
    import moment from "moment";
    import {request} from "../../util/request";
    import {profileUpdated} from "../../util/notify";

    const DEFAULT_FORM_STATE = {
        first_name: null,
        last_name: null,
        password: null,
        email: null,
        gender: null,
        birthday: null,
    };

    export default {
        data() {
            return {
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({})
            }
        },
        mounted() {
            const user = getAuthUser();
            this.formFields = {...this.formFields, ...user,  birthday: moment(user.birthday), gender: user.gender.toString()};
        },
        methods: {
            handleFormSubmit() {
                request({
                    method: "post",
                    url: `users/profile`,
                    data: {...this.formFields, birthday: ((this.formFields.birthday) ? moment(this.formFields.birthday).format('YYYY-MM-DD') : null)},
                })
                    .then((response) => {
                        const {data} = response;
                        const user = {...getAuthUser(), ...data};
                        removeStorage('auth');
                        setStorage('auth', JSON.stringify(user));
                        profileUpdated();
                        setTimeout(() => refresh(), 2000);
                    })
                    .catch((errors) => {
                        if(errors.status && errors.status === 422) {
                            this.formErrors = new Error(errors.data.errors);
                        }

                        handleServerError(errors);
                    });
            },
        }
    }
</script>
