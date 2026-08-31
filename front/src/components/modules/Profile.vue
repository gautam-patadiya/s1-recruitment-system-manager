<template>
    <form @submit.prevent="handleFormSubmit" autocomplete="off">
        <b-card class="profile-card">
            <div class="profile-card-header">
                <div>
                    <h3>Profile Information</h3>
                    <p>Update your account details and basic personal information.</p>
                </div>
                <b-button variant="primary" type="submit" :disabled="loading">
                    <i class="bi bi-check-circle mr-5"></i>
                    {{ loading ? 'Updating...' : 'Update Profile' }}
                </b-button>
            </div>

            <b-row>
                <b-col cols="12" md="6">
                    <b-form-group label="First Name *" class="mb-15 required-input">
                        <b-form-input
                            v-model="formFields.first_name"
                            :class="{ 'is-invalid': formErrors.has('first_name') }"
                        />
                        <div class="invalid-feedback d-block" v-if="formErrors.has('first_name')">
                            {{formErrors.first('first_name')}}
                        </div>
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                    <b-form-group label="Last Name *" class="mb-15 required-input">
                        <b-form-input
                            v-model="formFields.last_name"
                            :class="{ 'is-invalid': formErrors.has('last_name') }"
                        />
                        <div class="invalid-feedback d-block" v-if="formErrors.has('last_name')">
                            {{formErrors.first('last_name')}}
                        </div>
                    </b-form-group>
                </b-col>
            </b-row>

            <b-row>
                <b-col cols="12" md="6">
                    <b-form-group label="Email *" class="mb-15 required-input">
                        <b-form-input
                            v-model="formFields.email"
                            :class="{ 'is-invalid': formErrors.has('email') }"
                        />
                        <div class="invalid-feedback d-block" v-if="formErrors.has('email')">
                            {{formErrors.first('email')}}
                        </div>
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                    <b-form-group label="Gender *" class="mb-15 required-input">
                        <v-select
                            v-model="formFields.gender"
                            :options="genderOptions"
                            label="text"
                            :reduce="option => option.value"
                            :class="{ 'is-invalid': formErrors.has('gender') }"
                            placeholder="Select Gender"
                        ></v-select>
                        <div class="invalid-feedback d-block" v-if="formErrors.has('gender')">
                            {{formErrors.first('gender')}}
                        </div>
                    </b-form-group>
                </b-col>
            </b-row>

            <b-row>
                <b-col cols="12" md="6">
                    <b-form-group label="Birthday *" class="mb-15 required-input">
                        <date-picker
                            class="profile-date-picker"
                            v-model="formFields.birthday"
                            format="DD-MM-YYYY"
                            :input-class="`form-control ${formErrors.has('birthday') ? 'is-invalid' : ''}`"
                            placeholder="Select Birthday"
                        />
                        <div class="invalid-feedback d-block" v-if="formErrors.has('birthday')">
                            {{formErrors.first('birthday')}}
                        </div>
                    </b-form-group>
                </b-col>
            </b-row>

            <div class="profile-password-box">
                <h5>Password</h5>
                <p>Leave password fields blank if you do not want to change it.</p>

                <b-row>
                    <b-col cols="12" md="6">
                        <b-form-group label="Password" class="mb-15">
                            <b-form-input
                                type="password"
                                v-model="formFields.password"
                                :class="{ 'is-invalid': formErrors.has('password') }"
                            />
                            <div class="invalid-feedback d-block" v-if="formErrors.has('password')">
                                {{formErrors.first('password')}}
                            </div>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12" md="6">
                        <b-form-group label="Confirm Password" class="mb-15">
                            <b-form-input
                                type="password"
                                v-model="formFields.password_confirmation"
                                :class="{ 'is-invalid': formErrors.has('password_confirmation') }"
                            />
                            <div class="invalid-feedback d-block" v-if="formErrors.has('password_confirmation')">
                                {{formErrors.first('password_confirmation')}}
                            </div>
                        </b-form-group>
                    </b-col>
                </b-row>
            </div>
        </b-card>
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
        password_confirmation: null,
        email: null,
        gender: null,
        birthday: null,
    };

    export default {
        data() {
            return {
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({}),
                loading: false,
                genderOptions: [
                    {value: '1', text: 'Male'},
                    {value: '2', text: 'Female'},
                ],
            }
        },
        mounted() {
            const user = getAuthUser();
            this.formFields = {
                ...this.formFields,
                ...user,
                birthday: (user.birthday ? moment(user.birthday).toDate() : null),
                gender: (user.gender ? user.gender.toString() : null),
            };
        },
        methods: {
            handleFormSubmit() {
                this.loading = true;
                this.formErrors = new Error({});

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
                    })
                    .finally(() => this.loading = false);
            },
        }
    }
</script>
