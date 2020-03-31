<template>
    <a-row>
        <a-col :md="24">
            <h1>Applied Jobs</h1>
        </a-col>
        <a-col :md="24">
            <div class="mr-15" v-if="contentLoading">
                <a-skeleton active :paragraph="false" v-for="index in 5" :key="index"></a-skeleton>
            </div>
            <div class="ant-list-item list-box mb-10" v-if="!contentLoading" v-for="(job, index) in jobApplications" :key="index">
                <div class="ant-list-item-meta">
                    <div class="ant-list-item-meta-content">
                        <h4 class="ant-list-item-meta-title">
                            <strong>Job Title:</strong> {{job.job.title}}
                        </h4>
                        <div class="ant-list-item-meta-description">
                            <strong>Applied at:</strong>
                            {{covertData(job.created_at)}}
                        </div>
                        <div class="ant-list-item-meta-description">
                            <strong>Status:</strong>
                            {{job._status_}}
                        </div>
                        <div class="ant-list-item-meta-description">
                            <a @click="handleJobDetailClick(job.id)">Open Job Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </a-col>

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
    </a-row>
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
                    this.jobApplications = data;
                })
                .catch((errors) => {
                    handleServerError(errors);
                })
                .finally(() => this.contentLoading = false);
            },
            covertData(date, toFormat = 'DD-MM-YYYY hh:mm A', fromFormat = 'YYYY-MM-DD HH:mm:ss') {
                return utcDateToLocalDate(date, toFormat, fromFormat)
            }
        }
    }
</script>
<style lang="scss">
    .list-box{
        background: white;
        padding: 12px;
        box-shadow: 0 0 4px #c8c8c8;
    }
</style>
