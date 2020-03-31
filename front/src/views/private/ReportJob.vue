<template>
    <div>
        <a-row :gutter="16" class="mb-15">
            <a-col>
                <h2 class="mb-0">
                    Jobs <strong>Overview</strong>
                </h2>
            </a-col>
        </a-row>
        <a-card title="Filters">
            <form @submit.prevent="loadList" autocomplete="off">
                <a-row>
                    <a-skeleton v-if="dropdowns.companies.loading || dropdowns.jobDepartments.loading" active :paragraph="false" v-for="index in 2" :key="index"></a-skeleton>
                    <a-col :xs="24" :sm="24" :md="24" v-if="!dropdowns.companies.loading && !dropdowns.jobDepartments.loading">
                        <a-row :gutter="18">
                            <a-col :xs="24" :sm="8" :md="6">
                                <a-form-item label="Companies" class="mb-0">
                                    <a-select v-model="filters.companies" style="width: 100%" mode="multiple">
                                        <a-select-option v-for="(company, index) in dropdowns.companies.dataSource" :key="index" :value="company.id">
                                            {{company.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="8" :md="6">
                                <a-form-item label="Job Department" class="mb-0">
                                    <a-select v-model="filters.jobDepartments" style="width: 100%" mode="multiple">
                                        <a-select-option v-for="(jobDepartment, index) in dropdowns.jobDepartments.dataSource" :key="index" :value="jobDepartment.id">
                                            {{jobDepartment.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="8" :md="6">
                                <a-form-item label="Job From" class="mb-0">
                                    <a-date-picker v-model="filters.fromJob" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="8" :md="6">
                                <a-form-item label="Job To" class="mb-0">
                                    <a-date-picker v-model="filters.toJob" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                        </a-row>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="24" v-if="!dropdowns.companies.loading && !dropdowns.jobDepartments.loading">
                        <a-row :gutter="18" v-if="!dropdowns.companies.loading">
                            <a-col :xs="24" :sm="8" :md="6">
                                <a-form-item label="Sort By" class="mb-0">
                                    <a-select v-model="filters.sortField" style="width: 100%" allowClear>
                                        <a-select-option value="company_id">Company</a-select-option>
                                        <a-select-option value="job_department_id">Job Department</a-select-option>
                                        <a-select-option value="from_date">From Date</a-select-option>
                                        <a-select-option value="to_date">To Date</a-select-option>
                                        <a-select-option value="archived_at">Archived Column</a-select-option>
                                        <a-select-option value="status">Active Column</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="8" :md="6">
                                <a-form-item label="Sort Order" class="mb-0">
                                    <a-select v-model="filters.sortOrder" style="width: 100%" allowClear>
                                        <a-select-option value="desc">Descending</a-select-option>
                                        <a-select-option value="asc">Ascending</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="10" :md="8">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="12">
                                        <a-form-item label=" " class="mb-0" :colon="false">
                                            <a-checkbox :checked="filters.archived_at" v-model="filters.archived_at">
                                                Archived
                                            </a-checkbox>
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="12">
                                        <a-form-item label=" " class="mb-0" :colon="false">
                                            <a-checkbox :checked="filters.expired" v-model="filters.expired">
                                                Expired
                                            </a-checkbox>
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                            </a-col>
                            <a-col :xs="24" :sm="6" :md="4">
                                <a-form-item label=" " class="mb-0" :colon="false">
                                    <a-button type="primary" html-type="submit">
                                        <a-icon type="filter" /> Apply
                                    </a-button>
                                    <a href="javascript:;" class="ml-10" title="Export PDF" @click="handlePdfExportClick">
                                        <a-icon type="file-pdf" />
                                    </a>
                                    <a href="javascript:;" class="ml-10" title="Export CSV" @click="handleCsvExportClick">
                                        <a-icon type="file-excel" />
                                    </a>
                                </a-form-item>
                            </a-col>
                        </a-row>
                    </a-col>
                </a-row>
            </form>
        </a-card>
        <a-card>
            <a-skeleton v-if="loading" active :paragraph="false" v-for="index in 15" :key="index"></a-skeleton>
            <div class="ant-table-wrapper" v-if="!loading">
                <a-table
                    class="fit-table report-jobs-table"
                    :columns="columns"
                    :rowKey="record => record.id"
                    :dataSource="dataSource"
                    :pagination="false"
                    :loading="loading">
                    <template slot="job_title" slot-scope="text, record, index">
                        <div>{{record.title}}</div>
                        <div> <strong>Company: </strong> {{record.company}} </div>
                        <div> <strong>Department: </strong> {{record.job_department}} </div>
                    </template>
                    <template slot="from_date" slot-scope="text, record, index">
                        <div> <strong>From Date: </strong> {{ covertDate(record.from_date) }}</div>
                        <div> <strong>To Date: </strong> {{ covertDate(record.to_date) }}</div>
                    </template>
                    <template slot="job_applications" slot-scope="text, record, index">
                        {{record.job_applications.length}}
                    </template>
                    <template slot="status" slot-scope="text, record, index">
                        <span v-if="record.status"><a-tag color="blue">Yes</a-tag></span>
                        <span v-if="!record.status"><a-tag color="red">No</a-tag></span>
                    </template>
                    <template slot="archived_at" slot-scope="text, record, index">
                        <span v-if="record.archived_at"><a-tag color="blue">Yes</a-tag></span>
                        <span v-if="!record.archived_at"><a-tag color="red">No</a-tag></span>
                    </template>
                </a-table>
            </div>
        </a-card>
    </div>
</template>

<script>
    import {request} from "../../util/request";
    import qs from "qs";
    import {dateToUtcDate, utcDateToLocalDate} from "../../util/utils";
    import moment from "moment-timezone";
    import mixins from "../../util/mixins";
    // @ is an alias to /src

    export default {
        mixins: [mixins],
        data() {
            return {
                loading: false,
                dataSource: [],
                columns: [
                    {
                        title: 'Job Title',
                        dataIndex: 'title',
                        width: 180,
                        scopedSlots: { customRender: 'job_title' },
                    },
                    {
                        title: 'Job Date',
                        dataIndex: 'from_date',
                        width: 180,
                        scopedSlots: { customRender: 'from_date' },
                    },
                    {
                        title: 'Is active?',
                        dataIndex: 'status',
                        width: 50,
                        scopedSlots: { customRender: 'status' },
                    },
                    {
                        title: 'Number of job applications',
                        dataIndex: 'job_applications',
                        width: 150,
                        scopedSlots: { customRender: 'job_applications' },
                    },
                    {
                        title: 'Is archived?',
                        dataIndex: 'archived_at',
                        width: 50,
                        scopedSlots: { customRender: 'archived_at' },
                    },
                ],
                filters: {
                    fromJob: undefined,
                    toJob: undefined,
                    sortOrder: null,
                    sortField: null,
                    archived_at: false,
                    expired: false,
                    now: moment().format('YYYY-MM-DD'),
                    companies: [],
                    jobDepartments: [],
                },
                dropdowns: {
                    companies: {
                        loading: false,
                        dataSource: []
                    },
                    jobDepartments: {
                        loading: false,
                        dataSource: []
                    }
                }
            }
        },
        mounted() {
            // this.loadList();
            this.getCompanies();
            this.getJobDepartments();
        },
        methods: {
            loadList() {
                this.loading = true;
                const listQueryParams = {
                    filters: {
                        ...this.filters,
                        fromJob: ((this.filters.fromJob) ? this.filters.fromJob.format('YYYY-MM-DD') : ''),
                        toJob: ((this.filters.toJob) ? this.filters.toJob.format('YYYY-MM-DD') : ''),
                    },
                };

                request({
                    method: "get",
                    url: `reports/job/overview`,
                    params: listQueryParams,
                    paramsSerializer: ((listQueryParams) => qs.stringify(listQueryParams)),
                })
                .then((response) => {
                    const {data} = response;
                    this.dataSource = data;
                })
                .catch(() => this.dataSource = [] )
                .finally(() => this.loading = false);
            },
            covertDate(date, toFormat = 'DD-MM-YYYY') {
                return moment(date).format(toFormat);
            },
            getCompanies() {
                this.dropdowns.companies.loading = true;
                request({
                    method: "GET",
                    url: `companies`,
                })
                .then((response) => {
                    this.dropdowns.companies.dataSource = response.data.map((item) => {
                        return {
                            id: item.id,
                            label: item.name,
                        }
                    })
                })
                .catch(() => {})
                .finally(() => this.dropdowns.companies.loading = false);
            },
            getJobDepartments() {
                this.dropdowns.jobDepartments.loading = true;
                request({
                    method: "GET",
                    url: `job/departments`,
                })
                .then((response) => {
                    this.dropdowns.jobDepartments.dataSource = response.data.map((item) => {
                        return {
                            id: item.id,
                            label: item.name
                        }
                    })
                })
                .catch(() => {})
                .finally(() => this.dropdowns.jobDepartments.loading = false);
            },
            handlePdfExportClick() {
                this.pdf.title = 'Jobs Overview';
                this.pdf.filename = 'jobs_overview';
                this.pdf.body = this.dataSource.map((item, index) => {
                    return {
                        ...this.exportFields(item),
                        index: (index + 1),
                    }
                });

                this.pdf.columns = [
                    {header: '#', dataKey: 'index'},
                    {header: 'Job Title', dataKey: 'job_title'},
                    {header: 'Date', dataKey: 'job_date'},
                    {header: 'Is Active?', dataKey: 'is_active'},
                    {header: 'Number of Application', dataKey: 'number_of_application'},
                    {header: 'Is Archived?', dataKey: 'is_archived'},
                ];

                this.exportPdf();
            },
            handleCsvExportClick() {
                this.csv.config.filename = 'Jobs Overview';
                this.csv.config.headers = ['Job Title', 'Date', 'Is Active?', 'Number of Application', 'is Archived?'];
                this.csv.dataSource = this.dataSource.map((item) => this.exportFields(item));
                this.exportCsv();
            },
            exportFields(item) {
                return {
                    job_title: item.title + ' || Company: '+ item.company +' || Department: '+item.job_department,
                    job_date: 'From Date: '+ this.covertDate(item.from_date) +' || To Date: '+this.covertDate(item.to_date),
                    is_active: ((item.status) ? 'Yes' : 'No'),
                    number_of_application: item.job_applications.length,
                    is_archived: ((item.is_archived) ? 'Yes' : 'No'),
                }
            }
        },
        watch: {
        }
    }
</script>

<style lang="scss">
    @media screen and (max-width: 790px) {
        .fit-table.report-jobs-table td:before{ font-weight: bold; }
        .fit-table.report-jobs-table td:nth-of-type(1):before { content: "Job Title"; }
        .fit-table.report-jobs-table td:nth-of-type(2):before { content: "Job Date"; }
        .fit-table.report-jobs-table td:nth-of-type(3):before { content: "Is Active?"; }
        .fit-table.report-jobs-table td:nth-of-type(4):before { content: "Number of Job Apps."; }
        .fit-table.report-jobs-table td:nth-of-type(5):before { content: "Is Archived"; }
    }

    @media only screen and (min-width: 768px) {
        .info-num{
            text-align: right;
        }

    }

    @media only screen and (max-width: 992px) {
        .mt-sm-20{
            margin-top: 20px;
        }
    }
</style>
