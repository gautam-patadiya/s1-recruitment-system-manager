<template>
    <div class="applied-jobs-section">
        <div class="profile-card applied-jobs-card">
            <div class="profile-card-header">
                <div>
                    <h3>Applied Jobs</h3>
                    <p>Track the jobs you have applied for and review details anytime.</p>
                </div>
                <span class="report-count-pill">{{jobApplications.length}}</span>
            </div>

            <b-row v-if="contentLoading">
                <b-col cols="12" md="6" v-for="index in 4" :key="index" class="mb-20">
                    <b-card class="applied-job-item-card">
                        <b-skeleton height="20px"></b-skeleton>
                        <b-skeleton width="70%" class="mt-3"></b-skeleton>
                        <b-skeleton width="45%" class="mt-3"></b-skeleton>
                    </b-card>
                </b-col>
            </b-row>

            <b-row v-if="!contentLoading && jobApplications.length">
                <b-col cols="12" md="6" v-for="job in jobApplications" :key="job.id" class="mb-20">
                    <b-card class="applied-job-item-card">
                        <div class="applied-job-top">
                            <span class="public-company-name">{{job.job && job.job.company ? job.job.company : 'Company'}}</span>
                            <span :class="['job-application-status-badge', applicationStatusClass(job.display_status)]">
                                {{job._display_status_ || job._status_}}
                            </span>
                        </div>

                        <h4>{{job.job ? job.job.title : '-'}}</h4>

                        <div class="applied-job-meta">
                            <span>
                                <i class="bi bi-calendar2-check mr-5"></i>
                                Applied at {{covertData(job.created_at)}}
                            </span>
                            <span>
                                <i class="bi bi-clock-history mr-5"></i>
                                {{importantDateLabel(job)}} {{importantDateValue(job)}}
                            </span>
                        </div>

                        <b-button variant="outline-primary" block class="applied-job-detail-button" @click="handleJobDetailClick(job.id)">
                            Open Job Detail
                        </b-button>
                    </b-card>
                </b-col>
            </b-row>

            <div class="public-empty-state" v-if="!contentLoading && jobApplications.length <= 0">
                <i class="bi bi-briefcase"></i>
                <h3>No applied jobs</h3>
                <p>You have not applied for any jobs yet.</p>
            </div>
        </div>

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
    import {handleServerError, utcDateToLocalDate} from "../../util/utils";
    import {request} from "../../util/request";

    export default {
        data() {
            return {
                jobApplications: [],
                contentLoading: false,
                selectedJob: null,
                openDetailedJob: false,
            }
        },
        mounted() {
            this.getAppliedJob();
        },
        methods: {
            handleJobDetailClose() {
                this.selectedJob = null;
                this.openDetailedJob = false;
            },
            handleJobDetailClick(jobId) {
                const jobApplication = this.jobApplications.filter((item) => item.id === jobId)[0];
                if(jobApplication) {
                    this.selectedJob = jobApplication.job;
                    this.openDetailedJob = true;
                }
            },
            getAppliedJob() {
                this.contentLoading = true;
                request({
                    method: "get",
                    url: `job/applications/own`,
                })
                .then((response) => {
                    const {data} = response;
                    this.jobApplications = data || [];
                })
                .catch((errors) => {
                    handleServerError(errors);
                })
                .finally(() => this.contentLoading = false);
            },
            applicationStatusClass(status) {
                if (Number(status) === 2) {
                    return 'job-application-status-scheduled';
                }
                if (Number(status) === 3) {
                    return 'job-application-status-hold';
                }
                if (Number(status) === 4) {
                    return 'job-application-status-rejected';
                }
                if (Number(status) === 5) {
                    return 'job-application-status-hired';
                }

                return 'job-application-status-pending';
            },
            importantDateLabel(job) {
                if (Number(job.display_status) === 5 && job.joining_date) {
                    return 'Joining date';
                }

                if (job.interview_date) {
                    return 'Interview date';
                }

                return 'Applied date';
            },
            importantDateValue(job) {
                if (Number(job.display_status) === 5 && job.joining_date) {
                    return this.covertData(job.joining_date, 'DD-MM-YYYY', 'YYYY-MM-DD');
                }

                if (job.interview_date) {
                    return this.covertData(job.interview_date);
                }

                return this.covertData(job.created_at);
            },
            covertData(date, toFormat = 'DD-MM-YYYY hh:mm A', fromFormat = 'YYYY-MM-DD HH:mm:ss') {
                if (!date) {
                    return '-';
                }

                return utcDateToLocalDate(date, toFormat, fromFormat)
            }
        }
    }
</script>
<style lang="scss">
    .applied-jobs-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
        padding: 20px;
    }

    .applied-job-item-card {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        height: 100%;
    }

    .applied-job-item-card .card-body {
        display: flex;
        flex-direction: column;
        padding: 20px;
    }

    .applied-job-top {
        align-items: center;
        display: flex;
        gap: 12px;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .applied-job-item-card h4 {
        color: #0f172a;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.4;
        margin-bottom: 14px;
    }

    .applied-job-meta {
        color: #64748b;
        display: grid;
        font-size: 13px;
        font-weight: 700;
        gap: 8px;
        margin-bottom: 18px;
    }

    .applied-job-detail-button {
        border-radius: 14px;
        font-weight: 800;
        margin-top: auto;
    }
</style>
