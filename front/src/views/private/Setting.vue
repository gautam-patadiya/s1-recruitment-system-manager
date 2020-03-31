<template>
    <a-row>
        <a-col span="24">
            <div>
                <a-row>
                    <a-col :xs="24" :sm="24" :md="12">
                        <h2 class="mb-0">
                            System<strong> Settings</strong>
                        </h2>
                    </a-col>
                </a-row>
            </div>
            <div class="mt-20">
                <form @submit.prevent="handleFormSubmit" autocomplete="off">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="12" :md="12">
                            <a-form-item label="Application Name" class="mb-10" help="Will appear in title">
                                <a-input type="text" v-model="formFields.items.app_name"/>
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="12">
                            <a-form-item label="Application URL" class="mb-10" help="Ex. exampledomain.com API URL will be exampledomain.com/api">
                                <a-input type="url" v-model="formFields.items.app_url" placeholder="Ex. AP-"/>
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="12" :md="12">
                            <a-form-item label="Logo Text Part One" class="mb-10">
                                <a-input type="text" v-model="formFields.items.text_logo_part_one" placeholder="Ex. John"/>
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="12">
                            <a-form-item label="Logo Text Part Two" class="mb-10">
                                <a-input type="text" v-model="formFields.items.text_logo_part_two" placeholder="Ex. Doe"/>
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="12" :md="12">
                            <a-form-item label="Home page title" class="mb-10">
                                <a-input type="text" v-model="formFields.items.home_page_title"/>
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="12">
                            <a-form-item label="Job Application Number Prefix" class="mb-10">
                                <a-input type="text" v-model="formFields.items.job_application_number_prefix" placeholder="Ex. AP-"/>
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="12" :md="12" class="control-line-height">
                            <a-form-item label="About Us" class="mb-10">
                                <editor
                                    props-tool-bar="2"
                                    props-upload-url="/document/uploads/miscellaneous"
                                    :props-content.sync="formFields.items.about_us_content"></editor>
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="12" :md="12" class="control-line-height">
                            <a-form-item label="User have to verify email after register"  class="mb-10">
                                <a-checkbox :checked="formFields.items.user_has_to_verify_email_after_register" v-model="formFields.items.user_has_to_verify_email_after_register">
                                    Yes
                                </a-checkbox>
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <div class="mt-5">
                        <a-button type="primary" html-type="submit" class="mr-5">Update</a-button>
                    </div>
                </form>
            </div>
        </a-col>
    </a-row>
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
                });
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
    .control-line-height{
        .ant-form-item-control{
            line-height: 25px;
        }
    }
</style>
