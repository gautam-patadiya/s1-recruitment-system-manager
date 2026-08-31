<template>
    <b-row>
        <b-col cols="12">
            <b-row class="align-items-center job-header-simple">
                <b-col cols="12" md="7">
                    <h2 class="job-header-title">
                        System Settings
                    </h2>
                </b-col>

                <b-col cols="12" md="5" class="text-md-right mt-15 mt-md-0">
                    <b-button variant="primary" type="submit" form="settings-form" :disabled="loading">
                        <i class="bi bi-check-circle mr-5"></i>
                        {{ loading ? 'Updating...' : 'Update Settings' }}
                    </b-button>
                </b-col>
            </b-row>
            <div class="mt-20">
                <form id="settings-form" @submit.prevent="handleFormSubmit" autocomplete="off">
                    <b-card class="settings-card mb-20">
                        <h5 class="settings-section-title">Application Details</h5>

                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group label="Application Name" class="mb-15" description="This name will appear in the browser title.">
                                    <b-form-input type="text" v-model="formFields.items.app_name"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Application URL" class="mb-15" description="Example: https://example.com">
                                    <b-form-input type="url" v-model="formFields.items.app_url"></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group label="Home Page Title" class="mb-15">
                                    <b-form-input type="text" v-model="formFields.items.home_page_title"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Job Application Number Prefix" class="mb-15">
                                    <b-form-input type="text" v-model="formFields.items.job_application_number_prefix" placeholder="Ex. AP-"></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-card>

                    <b-card class="settings-card mb-20">
                        <h5 class="settings-section-title">Branding</h5>

                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group label="Logo Text Part One" class="mb-15">
                                    <b-form-input type="text" v-model="formFields.items.text_logo_part_one" placeholder="Ex. Satt"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Logo Text Part Two" class="mb-15">
                                    <b-form-input type="text" v-model="formFields.items.text_logo_part_two" placeholder="Ex. Viki"></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-card>

                    <b-card class="settings-card mb-20">
                        <h5 class="settings-section-title">Content</h5>

                        <b-row>
                            <b-col cols="12" class="control-line-height">
                                <b-form-group label="About Us" class="mb-15">
                                    <editor
                                        props-tool-bar="2"
                                        props-upload-url="/document/uploads/miscellaneous"
                                        :props-content.sync="formFields.items.about_us_content"></editor>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-card>

                    <b-card class="settings-card">
                        <h5 class="settings-section-title">Registration</h5>

                        <b-form-group label="User has to verify email after register" class="mb-0">
                            <b-form-checkbox v-model="formFields.items.user_has_to_verify_email_after_register">
                                Yes, require email verification
                            </b-form-checkbox>
                        </b-form-group>
                    </b-card>

                    <div class="settings-footer text-right mt-20">
                        <b-button variant="primary" type="submit" :disabled="loading">
                            <i class="bi bi-check-circle mr-5"></i>
                            {{ loading ? 'Updating...' : 'Update Settings' }}
                        </b-button>
                    </div>
                </form>
            </div>
        </b-col>
    </b-row>
</template>
<script>
    import {request} from "../../util/request";
    import mixins from "../../util/mixins";
    import Error from "../../util/Error";
    import {handleServerError, refresh} from "../../util/utils";
    import { settingUpdated } from "../../util/notify";
    import Editor from "../../components/shared/Editor";
    import {mapFields} from "vuex-map-fields";

    const DEFAULT_FORM_STATE = {
        items: {},
    };

    export default {
        mixins: [mixins],
        components: {
            Editor
        },
        data() {
            return {
                formTitle: 'Update Setting',
                loading: false,
                formVisible: true,
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({}),
                content: ''
            }
        },
        mounted() {
            const {
                app_name, app_url, home_page_title, about_us_content,
                job_application_number_prefix,
                user_has_to_verify_email_after_register, text_logo_part_one, text_logo_part_two
            } = this.settings;

            this.formFields.items = {
                app_name: (app_name ? app_name : null),
                app_url: (app_url ? app_url : null),
                home_page_title: (home_page_title ? home_page_title : null),
                about_us_content: (about_us_content ? about_us_content : null),
                job_application_number_prefix: (job_application_number_prefix ? job_application_number_prefix : null),
                text_logo_part_one: (text_logo_part_one ? text_logo_part_one : null),
                text_logo_part_two: (text_logo_part_two ? text_logo_part_two : null),
                user_has_to_verify_email_after_register: (!!(user_has_to_verify_email_after_register && user_has_to_verify_email_after_register === 'yes')),
            }
        },
        methods: {
            handleFormSubmit() {
                this.loading = true;
                this.formErrors = new Error({});

                request({
                    method: "post",
                    url: 'settings',
                    data: {...this.formFields['items']},
                })
                .then((response) => {
                    settingUpdated();

                    setTimeout(() => {
                        refresh();
                    },2000);
                })
                .catch((errors) => {
                    if(errors.status && errors.status === 422) {
                        this.formErrors = new Error(errors.data.errors);
                    }

                    handleServerError(errors);
                })
                .finally(() => this.loading = false);
            },
        },
        computed: {
            ...mapFields([
                'settings'
            ])
        }
    }
</script>
<style lang="scss">
    .settings-card {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
    }

    .settings-section-title {
        color: #0f172a;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 18px;
    }

    .settings-footer {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
    }

    .control-line-height{
        .form-group{
            line-height: 25px;
        }
    }
</style>
