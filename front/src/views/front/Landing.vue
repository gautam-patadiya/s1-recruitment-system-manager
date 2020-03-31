<template>
    <div class="container">
        <a-row class="back-gray-2 wrapper">
            <a-col :xs="24" :sm="12" :md="6">
                <div class="mr-15" v-if="sideLoading">
                    <a-skeleton active :paragraph="false" v-for="index in 10" :key="index"></a-skeleton>
                </div>
                <a-menu mode="vertical" class="mr-15 back-gray-1 sidebar" v-if="!sideLoading" :defaultSelectedKeys="selectedJobDepartment">
                    <a-menu-item :key="job.id" v-for="(job, index) in dropdowns.job_departments" @click="selectedJobDepartment = [job.id]" :title="job.name">
                        {{ job.name }}
                    </a-menu-item>
                </a-menu>
            </a-col>
            <a-col :xs="24" :sm="12" :md="18">
                <div class="mr-15" v-if="contentLoading">
                    <a-skeleton active :paragraph="false" v-for="index in 10" :key="index"></a-skeleton>
                </div>
                <a-row  v-if="!contentLoading" :gutter="16">
                    <div>
                        <a-col :md="24">
                            <h1>{{title}}</h1>
                        </a-col>
                    </div>
                    <a-col :xs="24" :sm="24" :md="12" class="mb-15" v-for="(job, index) in dropdowns.jobs" :key="index">
                        <a-card size="small" :title="null" class="custom-card">
                            <div class="body">
                                <p> {{job.title}} </p>
                                <p> Company: {{job.company}} </p>
                                <div class="text-right">
                                    <a-tag v-for="(el, elIndex) in job.experience_levels" :key="elIndex">{{el.title}}</a-tag>
                                </div>
                            </div>
                            <span class="apply-button mt-10">
                                <a-row>
                                    <a-col :sm="12" class="text-left">
                                        <a @click="handleDetailClick(job.id)">Apply</a>
                                    </a-col>
                                    <a-col :sm="12" class="text-right">
                                        Vacancies: {{job.open_vacancies}}
                                    </a-col>
                                </a-row>
                            </span>
                        </a-card>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" class="mb-15" v-if="dropdowns.jobs.length <= 0">
                        No data available!
                    </a-col>
                </a-row>
            </a-col>
        </a-row>

        <a-drawer
            width="800"
            title="Job Detail"
            placement="right"
            :closable="false"
            @close="handleJobDetailClose"
            :visible="openDetailedJob"
            :wrapStyle="{height: 'calc(100% - 108px)',overflow: 'auto', paddingBottom: '108px'}"
        >
            <template v-if="selectedJob && selectedJob.title">
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="16" :lg="16">
                        <a-form-item :validate-status="(formErrors.has('user_document_id') ? 'error' : '')"
                                     :help="formErrors.first('user_document_id')" class="mb-0">
                            <a-select placeholder="Select Document" allowClear v-model="formFields.user_document_id"
                                      :dropdownMatchSelectWidth="false" style="width: 100%">
                                <a-select-option v-for="(user_document, index) in dropdowns.user_documents" :key="index" :value="user_document.id">
                                    {{user_document.label}}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="8" :lg="8">
                        <a-button
                            type="primary"
                            class="mr-5" block
                            @click="handleApplyEvent(selectedJob.id)">
                            Apply
                        </a-button>
                    </a-col>
                </a-row>
                <div class="div-saperator"/>
                <h2>{{selectedJob.title}}</h2>
                <h4>
                    <strong>About job:</strong>
                </h4>
                <div v-html="selectedJob.job_description"></div>
                <br>
                <h4>
                    <strong>About {{selectedJob.company}}</strong>
                </h4>
                <div v-html="selectedJob.company_description"></div>
                <div v-if="selectedJob.qualifications.length > 0">
                    <div class="div-saperator"/>
                    <h4><strong>Preferred Qualifications:</strong></h4>
                    <div>
                        {{selectedJob.qualifications.map((item) => item.title).join(', ')}}
                    </div>
                </div>
                <div v-if="selectedJob.experience_levels.length > 0">
                    <div class="div-saperator"/>
                    <h4><strong>Experience Levels:</strong></h4>
                    <div>
                        {{selectedJob.experience_levels.map((item) => item.title).join(', ')}}
                    </div>
                </div>
            </template>
        </a-drawer>
    </div>
</template>
<script>
    import {request} from "../../util/request";
    import qs from "qs";
    import {getAuthUser, handleServerError} from "../../util/utils";
    import {jobApplyAlready, jobApplyFailed, jobApplySuccess} from "../../util/notify";
    import Error from "../../util/Error";
    import {mapFields} from 'vuex-map-fields';

    const DEFAULT_FORM_STATE = {
        user_document_id: undefined,
        status: 1,
    };

    const USER = getAuthUser(false);

    export default {
        data() {
            return {
                sideLoading: true,
                contentLoading: false,
                selectedJobDepartment: null,
                selectedJob: null,
                openDetailedJob: false,
                isSubmitting: false,
                formErrors: new Error({}),
                formFields: {...DEFAULT_FORM_STATE},
                title: '',
                dropdowns: {
                    user_documents: [],
                    job_departments: [],
                    jobs: [],
                }
            }
        },
        mounted() {
            this.getJobDepartments();
            this.getUserOwnDocs();
            this.title = this.settings.home_page_title;
        },
        methods: {
            handleDetailClick(id) {
                this.selectedJob = this.dropdowns.jobs.filter((item) => item.id === id);
                if(this.selectedJob) {
                    this.selectedJob = this.selectedJob[0];
                    this.openDetailedJob = true;
                    if (Object.keys(USER).length <= 0) {
                        return this.$router.push({name: 'login'});
                    }
                }
            },
            handleJobDetailClose() {
                this.selectedJob = null;
                this.openDetailedJob = false;
            },
            handleApplyEvent(jobId) {
                if(this.isSubmitting) {
                    return false;
                }
                this.formErrors = new Error({});
                this.isSubmitting = true;

                if (Object.keys(USER).length <= 0) {
                    return this.$router.push({name: 'login'});
                }

                request({
                    method: "post",
                    url: `job/applications/create`,
                    data: {
                        ...this.formFields,
                        job_id: jobId,
                        candidate_id: USER.id,
                    },
                })
                .then((response) => {
                    if(response.code === 201) {
                        jobApplyAlready();
                        return false;
                    }

                    jobApplySuccess();
                    this.formFields = {...DEFAULT_FORM_STATE};
                    this.selectedJob = null;
                    this.openDetailedJob = false;
                })
                .catch((errors) => {
                    if(errors.status && errors.status === 422) {
                        this.formErrors = new Error(errors.data.errors);
                        jobApplyFailed();
                    }

                    handleServerError(errors);
                })
                .finally(() => this.isSubmitting = false);
            },
            getJobDepartments() {
                this.sideLoading = true;
                this.dropdowns.job_departments = [];
                request({
                    method: "get",
                    url: `job/departments`,
                })
                    .then((response) => {
                        const {data} = response;
                        this.dropdowns.job_departments = data;
                        if(data && data.length > 0) {
                            this.selectedJobDepartment = data ? [data[0].id] : null;
                        }
                    })
                    .catch((errors) => {
                        this.dropdowns.job_departments = [];
                        this.contentLoading = false;
                    })
                    .finally(() => this.sideLoading = false);
            },
            getDepartmentJobs() {
                if (!this.selectedJobDepartment) {
                    this.dropdowns.jobs = [];
                    return;
                }
                const listQueryParams = {
                    filters : {
                        job_department_id: this.selectedJobDepartment[0],
                        unarchived_at: 'true'
                    }
                };

                this.contentLoading = true;
                this.dropdowns.jobs = [];
                request({
                    method: "get",
                    url: `jobs`,
                    params: listQueryParams,
                    paramsSerializer: ((listQueryParams) => qs.stringify(listQueryParams)),
                })
                    .then((response) => {
                        const {data} = response;
                        this.dropdowns.jobs = data ?
                            data.filter((item) => item.status > 0)
                            : [];
                    })
                    .catch(() => this.dropdowns.jobs = [] )
                    .finally(() => this.contentLoading = false);
            },
            getUserOwnDocs() {
                this.dropdowns.user_documents = [];
                request({
                    url: `/document/uploads/users`,
                })
                .then((response) => {
                    this.dropdowns.user_documents = response.data.map((item) => {
                        return {
                            id: item.id,
                            label: `${item.document_type} - ${item.filename}`
                        }
                    });
                })
                .catch(() => {
                    this.dropdowns.user_documents = [];
                });
            },
        },
        watch: {
            selectedJobDepartment: function(value, index) {
                this.getDepartmentJobs();
            }
        },
        computed: {
            ...mapFields([
                'settings'
            ])
        }
    }
</script>
