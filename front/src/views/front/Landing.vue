<template>
    <div class="public-jobs-page">
        <section class="public-hero-section">
            <div class="container">
                <b-row class="align-items-center">
                    <b-col cols="12" lg="7">
                        <span class="public-hero-badge">
                            <i class="bi bi-stars mr-5"></i>
                            Hiring Portal
                        </span>
                        <h1>{{title || 'Find your next opportunity'}}</h1>
                        <p>Explore open roles, review basic job details, and apply with your uploaded document.</p>
                    </b-col>
                    <b-col cols="12" lg="5" class="mt-20 mt-lg-0">
                        <div class="public-hero-card">
                            <span>Open Jobs</span>
                            <strong>{{ activeJobsCount }}</strong>
                            <p>{{ activeDepartmentName }}</p>
                        </div>
                    </b-col>
                </b-row>
            </div>
        </section>

        <section class="public-job-section">
            <div class="container">
                <b-row>
                    <b-col cols="12">
                        <div class="public-section-header">
                            <div>
                                <h2>Open Positions</h2>
                                <p>Select a department and choose the role you want to apply for.</p>
                            </div>
                        </div>
                    </b-col>
                </b-row>

                <div class="public-department-list" v-if="sideLoading">
                    <b-skeleton v-for="index in 5" :key="index" width="130px" height="38px"></b-skeleton>
                </div>

                <div class="public-department-list" v-if="!sideLoading">
                    <button
                        type="button"
                        class="public-department-pill"
                        :class="{ active: selectedJobDepartment && selectedJobDepartment.includes(job.id) }"
                        :key="job.id"
                        v-for="job in dropdowns.job_departments"
                        @click="selectJobDepartment(job.id)"
                        :title="job.name"
                    >
                        {{ job.name }}
                    </button>
                </div>

                <b-row v-if="contentLoading">
                    <b-col cols="12" md="6" lg="4" class="mb-20" v-for="index in 6" :key="index">
                        <b-card class="public-job-card">
                            <b-skeleton height="22px"></b-skeleton>
                            <b-skeleton width="70%" class="mt-3"></b-skeleton>
                            <b-skeleton width="45%" class="mt-4"></b-skeleton>
                        </b-card>
                    </b-col>
                </b-row>

                <b-row v-if="!contentLoading && dropdowns.jobs.length">
                    <b-col cols="12" md="6" lg="4" class="mb-20" v-for="job in dropdowns.jobs" :key="job.id">
                        <b-card class="public-job-card">
                            <div class="public-job-card-header">
                                <span class="public-company-name">{{job.company || 'Company'}}</span>
                                <span class="job-vacancy-pill">{{job.open_vacancies || 0}} Open</span>
                            </div>

                            <h3>{{job.title}}</h3>

                            <div class="public-job-tags">
                                <b-badge
                                    class="public-job-tag"
                                    v-for="(el, elIndex) in job.experience_levels"
                                    :key="elIndex"
                                >
                                    {{el.title}}
                                </b-badge>
                            </div>

                            <b-button variant="primary" block class="public-apply-button" @click="handleDetailClick(job.id)">
                                View & Apply
                            </b-button>
                        </b-card>
                    </b-col>
                </b-row>

                <div class="public-empty-state" v-if="!contentLoading && dropdowns.jobs.length <= 0">
                    <i class="bi bi-briefcase"></i>
                    <h3>No jobs available</h3>
                    <p>Please check another department or come back later.</p>
                </div>
            </div>
        </section>

        <b-sidebar
            width="800px"
            title="Job Detail"
            right
            shadow
            backdrop
            no-header-close
            sidebar-class="job-detail-sidebar"
            body-class="job-detail-sidebar-body"
            v-model="openDetailedJob"
            @hidden="handleJobDetailClose"
        >
            <template v-if="selectedJob && selectedJob.title">
                <div class="job-detail-content">
                    <div class="job-detail-hero">
                        <span class="public-company-name">{{selectedJob.company || 'Company'}}</span>
                        <h2>{{selectedJob.title}}</h2>
                        <span class="job-vacancy-pill" v-if="selectedJob.open_vacancies">
                            {{selectedJob.open_vacancies}} Open
                        </span>
                    </div>

                    <div class="job-detail-apply-box">
                        <b-row>
                            <b-col cols="12" md="8">
                                <b-form-group label="Select Document" class="mb-15 mb-md-0">
                                    <v-select
                                        v-model="formFields.user_document_id"
                                        :options="dropdowns.user_documents"
                                        label="label"
                                        :reduce="document => document.id"
                                        :class="{ 'is-invalid': formErrors.has('user_document_id') }"
                                        placeholder="Select Document"
                                    ></v-select>
                                    <div class="invalid-feedback d-block" v-if="formErrors.has('user_document_id')">
                                        {{formErrors.first('user_document_id')}}
                                    </div>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-button
                                    variant="primary"
                                    block
                                    class="job-detail-apply-button"
                                    :disabled="isSubmitting"
                                    @click="handleApplyEvent(selectedJob.id)">
                                    {{ isSubmitting ? 'Applying...' : 'Apply Now' }}
                                </b-button>
                            </b-col>
                        </b-row>
                    </div>

                    <div class="job-detail-section">
                        <h4>About Job</h4>
                        <div class="job-detail-text" v-html="selectedJob.job_description || '-'"></div>
                    </div>

                    <div class="job-detail-section">
                        <h4>About {{selectedJob.company || 'Company'}}</h4>
                        <div class="job-detail-text" v-html="selectedJob.company_description || '-'"></div>
                    </div>

                    <div class="job-detail-section" v-if="selectedJob.qualifications && selectedJob.qualifications.length > 0">
                        <h4>Preferred Qualifications</h4>
                        <div class="job-detail-chip-list">
                            <span class="job-detail-chip" v-for="item in selectedJob.qualifications" :key="item.id || item.title">
                                {{item.title}}
                            </span>
                        </div>
                    </div>

                    <div class="job-detail-section" v-if="selectedJob.experience_levels && selectedJob.experience_levels.length > 0">
                        <h4>Experience Levels</h4>
                        <div class="job-detail-chip-list">
                            <span class="job-detail-chip" v-for="item in selectedJob.experience_levels" :key="item.id || item.title">
                                {{item.title}}
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </b-sidebar>
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
            selectJobDepartment(id) {
                this.selectedJobDepartment = [id];
            },
            handleDetailClick(id) {
                this.selectedJob = this.dropdowns.jobs.find((item) => item.id === id);
                if(this.selectedJob) {
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
                        this.dropdowns.job_departments = data || [];
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
            ]),
            activeJobsCount() {
                return this.dropdowns.jobs.length;
            },
            activeDepartmentName() {
                if (!this.selectedJobDepartment) {
                    return 'Select a department';
                }

                const department = this.dropdowns.job_departments.find((item) => {
                    return item.id === this.selectedJobDepartment[0];
                });

                return department ? department.name : 'Selected Department';
            }
        }
    }
</script>
