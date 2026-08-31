<template>
    <div>
        <b-row class="align-items-center job-header-simple">
            <b-col cols="12" md="6">
                <h2 class="job-header-title">
                    Jobs Overview
                </h2>
            </b-col>

            <b-col cols="12" md="6" class="text-md-right mt-15 mt-md-0">
                <b-button variant="outline-secondary" class="mr-10" @click="handleCsvExportClick">
                    <i class="bi bi-filetype-csv mr-5"></i>
                    Export CSV
                </b-button>
                <b-button variant="outline-secondary" @click="handlePdfExportClick">
                    <i class="bi bi-file-earmark-pdf mr-5"></i>
                    Export PDF
                </b-button>
            </b-col>
        </b-row>

        <b-card class="report-filter-card mb-20">
            <h5 class="report-filter-title">Filters</h5>
            <form @submit.prevent="loadList" autocomplete="off">
                <b-row>
                    <b-col cols="12" v-if="dropdowns.companies.loading || dropdowns.jobDepartments.loading">
                        <b-skeleton v-for="index in 2" :key="index"></b-skeleton>
                    </b-col>
                    <b-col cols="12" v-if="!dropdowns.companies.loading && !dropdowns.jobDepartments.loading">
                        <b-row>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Companies" class="mb-15">
                                    <v-select
                                        v-model="filters.companies"
                                        :options="dropdowns.companies.dataSource"
                                        label="label"
                                        :reduce="company => company.id"
                                        multiple
                                        placeholder="Select Companies"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Job Department" class="mb-15">
                                    <v-select
                                        v-model="filters.jobDepartments"
                                        :options="dropdowns.jobDepartments.dataSource"
                                        label="label"
                                        :reduce="department => department.id"
                                        multiple
                                        placeholder="Select Job Departments"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Job From" class="mb-15">
                                    <date-picker v-model="filters.fromJob" format="DD-MM-YYYY" value-type="YYYY-MM-DD" placeholder="" />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Job To" class="mb-15">
                                    <date-picker v-model="filters.toJob" format="DD-MM-YYYY" value-type="YYYY-MM-DD" placeholder="" />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Sort By" class="mb-15">
                                    <v-select
                                        v-model="filters.sortField"
                                        :options="sortFieldOptions"
                                        label="text"
                                        :reduce="option => option.value"
                                        placeholder="Sort By"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Sort Order" class="mb-15">
                                    <v-select
                                        v-model="filters.sortOrder"
                                        :options="sortOrderOptions"
                                        label="text"
                                        :reduce="option => option.value"
                                        placeholder="Sort Order"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Other Filters" class="mb-15">
                                    <b-form-checkbox v-model="filters.archived_at" class="mb-5">
                                        Archived
                                    </b-form-checkbox>
                                    <b-form-checkbox v-model="filters.expired">
                                        Expired
                                    </b-form-checkbox>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4" class="d-flex align-items-end">
                                <b-form-group class="mb-15 w-100">
                                    <b-button variant="primary" type="submit">
                                        <i class="bi bi-check2-circle mr-5"></i>
                                        Apply Filters
                                    </b-button>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
            </form>
        </b-card>
        <b-card>
            <b-skeleton-table v-if="loading" :rows="15" :columns="columns.length"></b-skeleton-table>
            <div v-if="!loading">
                <b-table
                    class="fit-table report-jobs-table report-modern-table"
                    responsive
                    hover
                    show-empty
                    empty-text="No data available"
                    :fields="columns"
                    :items="dataSource"
                    :busy="loading"
                >
                    <template #cell(title)="data">
                        <div class="job-title-cell">
                            <div class="job-title-text">{{data.item.title}}</div>
                            <div class="job-meta-line">
                                {{data.item.company}} <span v-if="data.item.job_department">- {{data.item.job_department}}</span>
                            </div>
                        </div>
                    </template>
                    <template #cell(from_date)="data">
                        <div class="report-date-text">
                            From {{ covertDate(data.item.from_date) }}
                        </div>
                        <div class="job-meta-line">
                            To {{ covertDate(data.item.to_date) }}
                        </div>
                    </template>
                    <template #cell(job_applications)="data">
                        <span class="report-count-pill">
                            {{(data.item.job_applications) ? data.item.job_applications.length : 0}}
                        </span>
                    </template>
                    <template #cell(status)="data">
                        <b-badge v-if="data.item.status" class="job-status-badge job-status-active">Active</b-badge>
                        <b-badge v-if="!data.item.status" class="job-status-badge job-status-inactive">Inactive</b-badge>
                    </template>
                    <template #cell(archived_at)="data">
                        <b-badge v-if="data.item.archived_at" class="job-application-status-badge job-application-status-hold">Archived</b-badge>
                        <b-badge v-if="!data.item.archived_at" class="job-application-status-badge job-application-status-hired">Open</b-badge>
                    </template>
                </b-table>
            </div>
        </b-card>
    </div>
</template>

<script>
    import {request} from "../../util/request";
    import qs from "qs";
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
                        key: 'title',
                        label: 'Job Title',
                    },
                    {
                        key: 'from_date',
                        label: 'Job Date',
                    },
                    {
                        key: 'status',
                        label: 'Is active?',
                    },
                    {
                        key: 'job_applications',
                        label: 'Number of job applications',
                    },
                    {
                        key: 'archived_at',
                        label: 'Is archived?',
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
                },
                sortFieldOptions: [
                    {value: 'company_id', text: 'Company'},
                    {value: 'job_department_id', text: 'Job Department'},
                    {value: 'from_date', text: 'From Date'},
                    {value: 'to_date', text: 'To Date'},
                    {value: 'archived_at', text: 'Archived Column'},
                    {value: 'status', text: 'Active Column'},
                ],
                sortOrderOptions: [
                    {value: 'desc', text: 'Descending'},
                    {value: 'asc', text: 'Ascending'},
                ],
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
                const filters = {
                    ...this.filters,
                    fromJob: ((this.filters.fromJob) ? moment(this.filters.fromJob).format('YYYY-MM-DD') : null),
                    toJob: ((this.filters.toJob) ? moment(this.filters.toJob).format('YYYY-MM-DD') : null),
                };

                if (!filters.companies.length) {
                    delete filters.companies;
                }

                if (!filters.jobDepartments.length) {
                    delete filters.jobDepartments;
                }

                if (!filters.fromJob) {
                    delete filters.fromJob;
                }

                if (!filters.toJob) {
                    delete filters.toJob;
                }

                if (!filters.sortField) {
                    delete filters.sortField;
                }

                if (!filters.sortOrder) {
                    delete filters.sortOrder;
                }

                if (!filters.archived_at) {
                    delete filters.archived_at;
                }

                if (!filters.expired) {
                    delete filters.expired;
                    delete filters.now;
                }

                const listQueryParams = {
                    filters: filters,
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
                    number_of_application: ((item.job_applications) ? item.job_applications.length : 0),
                    is_archived: ((item.archived_at) ? 'Yes' : 'No'),
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
