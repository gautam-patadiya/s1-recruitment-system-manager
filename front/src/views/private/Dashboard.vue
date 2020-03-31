<template>
    <div>
        <a-row :gutter="16" class="mb-15">
            <a-col>
                <h2 class="mb-0">
                    Dash<strong>board</strong>
                </h2>
            </a-col>
        </a-row>
        <a-row :gutter="16">
            <a-col :xs="24" :sm="6">
                <a-card>
                    <a-skeleton v-if="infoBoxes.loading" active :paragraph="false" v-for="index in 1" :key="index"></a-skeleton>
                    <a-row v-if="!infoBoxes.loading">
                        <a-col :xs="24" :sm="24" :lg="15" :md="15">
                            <h4 class="mb-0"><strong>USERS</strong></h4>
                            <small class="text-gray-v2">Total Users</small>
                        </a-col>
                        <a-col :xs="24" :sm="24" :lg="9" :md="9" class="info-num">
                            <h1 class="text-info mb-0"><strong>{{infoBoxes.users}}</strong></h1>
                        </a-col>
                    </a-row>
                </a-card>
            </a-col>
            <a-col :xs="24" :sm="6">
                <a-card>
                    <a-skeleton v-if="infoBoxes.loading" active :paragraph="false" v-for="index in 1" :key="index"></a-skeleton>
                    <a-row v-if="!infoBoxes.loading">
                        <a-col :xs="24" :sm="24" :lg="15" :md="15">
                            <h4 class="mb-0"><strong>CANDIDATES</strong></h4>
                            <small class="text-gray-v2">Total Candidates</small>
                        </a-col>
                        <a-col :xs="24" :sm="24" :lg="9" :md="9" class="info-num">
                            <h1 class="text-info mb-0"><strong>{{infoBoxes.candidates}}</strong></h1>
                        </a-col>
                    </a-row>
                </a-card>
            </a-col>
            <a-col :xs="24" :sm="6">
                <a-card>
                    <a-skeleton v-if="infoBoxes.loading" active :paragraph="false" v-for="index in 1" :key="index"></a-skeleton>
                    <a-row v-if="!infoBoxes.loading">
                        <a-col :xs="24" :sm="24" :lg="15" :md="15">
                            <h4 class="mb-0"><strong>COMPANIES</strong></h4>
                            <small class="text-gray-v2">Total Companies</small>
                        </a-col>
                        <a-col :xs="24" :sm="24" :lg="9" :md="9" class="info-num">
                            <h1 class="text-info mb-0"><strong>{{infoBoxes.companies}}</strong></h1>
                        </a-col>
                    </a-row>
                </a-card>
            </a-col>
            <a-col :xs="24" :sm="6">
                <a-card>
                    <a-skeleton v-if="infoBoxes.loading" active :paragraph="false" v-for="index in 1" :key="index"></a-skeleton>
                    <a-row v-if="!infoBoxes.loading">
                        <a-col :xs="24" :sm="24" :lg="15" :md="15">
                            <h4 class="mb-0"><strong>ACTIVE JOBS</strong></h4>
                            <small class="text-gray-v2">Total Active Jobs</small>
                        </a-col>
                        <a-col :xs="24" :sm="24" :lg="9" :md="9" class="info-num">
                            <h1 class="text-info mb-0"><strong>{{infoBoxes.activeJobs}}</strong></h1>
                        </a-col>
                    </a-row>
                </a-card>
            </a-col>
        </a-row>

        <a-row :gutter="16" class="mt-20">
            <a-col :xs="24" :sm="24" :lg="12" :md="12">
                <a-card>
                    <h3 class="mb-20">
                        Jobs That Expires Today
                    </h3>
                    <a-skeleton v-if="lists.expireJobs.loading" active :paragraph="false" v-for="index in 7" :key="index"></a-skeleton>
                    <a-table
                    v-if="!lists.expireJobs.loading"
                    :columns="lists.expireJobs.columns"
                    :rowKey="record => record.id"
                    :dataSource="lists.expireJobs.dataSource"
                    :pagination="lists.expireJobs.pagination"
                    @change="handleExpireTableChange">
                    <template slot="iIndex" slot-scope="text, record, index">
                        {{index + ((lists.expireJobs.pagination.current_page-1) * 8) + 1 }}
                    </template>
                    <template slot="company" slot-scope="text, record, index">
                        {{record.company}}
                    </template>
                    <template slot="date" slot-scope="text, record, index">
                        <p>
                            {{ covertData(record.from_date, 'DD.MM', 'YYYY-MM-DD') }}-{{ covertData(record.to_date, 'DD.MM', 'YYYY-MM-DD') }}
                        </p>
                    </template>
                </a-table>
                </a-card>
            </a-col>
            <a-col :xs="24" :sm="24" :lg="12" :md="12" class="mt-sm-20">
                <a-card>
                    <h3 class="mb-20">Latest Job Applications</h3>
                    <a-skeleton v-if="lists.latestJobApplications.loading" active :paragraph="false" v-for="index in 7" :key="index"></a-skeleton>
                    <a-table
                    v-if="!lists.latestJobApplications.loading"
                    :columns="lists.latestJobApplications.columns"
                    :rowKey="record => record.id"
                    :dataSource="lists.latestJobApplications.dataSource"
                    :pagination="lists.latestJobApplications.pagination"
                    @change="handleJobAppTableChange">
                    <template slot="iIndex" slot-scope="text, record, index">
                        {{index + ((lists.latestJobApplications.pagination.current_page-1) * 8) + 1 }}
                    </template>
                    <template slot="candidate_name" slot-scope="text, record, index">
                        {{record.candidate.first_name}} {{record.candidate.last_name}}
                    </template>
                </a-table>
                </a-card>
            </a-col>
        </a-row>

        <a-row :gutter="16" class="mt-20">
            <a-col :xs="24" :sm="24" :lg="12" :md="12">
                <a-card>
                    <h3 class="mb-20">
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="12">
                                Number of Jobs By Company
                            </a-col>
                            <a-col :xs="24" :sm="12">
                                <a-range-picker @change="expireJobRange"/>
                            </a-col>
                        </a-row>
                    </h3>
                    <a-skeleton v-if="charts.companyJobs.loading" active :paragraph="false" v-for="index in 7" :key="index"></a-skeleton>
                    <template v-if="!charts.companyJobs.loading">
                        <chart :options="charts.companyJobs"></chart>
                    </template>
                </a-card>
            </a-col>
            <a-col :xs="24" :sm="24" :lg="12" :md="12" class="mt-sm-20">
                <a-card>
                    <h3 class="mb-20">Monthly Number of Jobs of Current Year</h3>
                    <a-skeleton v-if="charts.monthlyJobApplications.loading" active :paragraph="false" v-for="index in 7" :key="index"></a-skeleton>
                    <template v-if="!charts.monthlyJobApplications.loading">
                        <chart :options="charts.monthlyJobApplications"></chart>
                    </template>
                </a-card>
            </a-col>
        </a-row>
    </div>
</template>

<script>
    import Dashboard from "../../components/modules/Dashboard";
    import {request} from "../../util/request";
    import moment from "moment-timezone";
    import qs from "qs";
    import {dateToUtcDate, utcDateToLocalDate} from "../../util/utils";
    import {Chart} from 'highcharts-vue'
    // @ is an alias to /src

    export default {
        components: {
            Dashboard,
            Chart
        },
        data() {
            return {
                infoBoxes: {
                    loading: false,
                    users: 0,
                    candidates: 0,
                    companies: 0,
                    activeJobs: 0,
                },
                lists: {
                    expireJobs: {
                        columns: [
                            {
                                title: '#',
                                dataIndex: 'created_at',
                                width: 20,
                                scopedSlots: { customRender: 'iIndex' },
                            },
                            {
                                title: 'Title',
                                dataIndex: 'title',
                                width: 150,
                            },
                            {
                                title: 'Company',
                                dataIndex: 'company_id',
                                width: 150,
                                scopedSlots: { customRender: 'company' },
                            },
                            {
                                title: 'Date',
                                dataIndex: 'date',
                                width: 150,
                                scopedSlots: { customRender: 'date' },
                            }
                        ],
                        dataSource: [],
                        loading: false,
                        pagination: {
                            page: 1,
                        },
                        listQueryParams: {},
                    },
                    latestJobApplications: {
                        columns: [
                            {
                                title: '#',
                                dataIndex: 'id',
                                width: 20,
                                scopedSlots: { customRender: 'iIndex' },
                            },
                            {
                                title: 'Number',
                                dataIndex: 'job_application_number',
                                width: 100,
                            },
                            {
                                title: 'Candidate',
                                dataIndex: 'candidate_id',
                                width: 150,
                                scopedSlots: { customRender: 'candidate_name' },
                            },
                            {
                                title: 'Applied At',
                                dataIndex: 'created_at',
                                width: 150,
                                scopedSlots: { customRender: 'applied_at' },
                            },
                        ],
                        dataSource: [],
                        loading: false,
                        pagination: {
                            page: 1,
                        },
                        listQueryParams: {},
                    }
                },
                charts: {
                    companyJobs: {
                        credits: {
                            enabled: false
                        },
                        loading: false,
                        title: { text: '' },
                        xAxis: {
                            categories: []
                        },
                        series: [
                            {
                                name: 'Job by Companies',
                                data: []
                            }
                        ]
                    },
                    monthlyJobApplications: {
                        credits: {
                            enabled: false
                        },
                        loading: false,
                        title: { text: '' },
                        xAxis: {
                            categories: [
                                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
                            ]
                        },
                        series: [
                            {
                                name: 'Job App. By Month',
                                data: []
                            }
                        ]
                    }
                },
                companyJobRevenueFrom: '',
                companyJobRevenueTo: '',
            }
        },
        mounted() {
            this.getInfoBoxes();
            this.getJobThatExpiresToday({ page: 1 });
            this.getJobApplication({ page: 1 });
            this.getCompanyJobRevenue();
            this.getMonthlyJobApplication();
        },
        methods: {
            getInfoBoxes() {
                this.infoBoxes.loading = true;
                request({
                    method: "get",
                    url: `/dashboard/infoboxes`
                })
                .then((response) => {
                    this.infoBoxes = {
                        users: response.data.users,
                        candidates: response.data.candidates,
                        companies: response.data.companies,
                        activeJobs: response.data.active_jobs,
                    };
                })
                .catch((errors) => {

                })
                .finally(() => this.infoBoxes.loading = false);
            },
            getJobThatExpiresToday(listQueryParams) {
                this.lists.expireJobs.loading = true;

                request({
                    url: `/dashboard/today/expires/jobs`,
                    params: {
                        today: moment().tz(moment.tz.guess()).utc().format('YYYY-MM-DD'),
                        // today: '2020-04-17',
                        ...listQueryParams
                    },
                    paramsSerializer: ((listQueryParams) => qs.stringify(listQueryParams)),
                })
                .then((response) => {
                    const {data, meta} = response;
                    this.lists.expireJobs.dataSource = data;
                    this.lists.expireJobs.pagination = {...meta, current: meta.current_page};
                })
                .catch(() => this.lists.expireJobs.dataSource = [] )
                .finally(() => {this.lists.expireJobs.loading = false;})
            },
            getJobApplication(listQueryParams) {
                this.lists.latestJobApplications.loading = true;

                request({
                    url: `/dashboard/latest/job/applications`,
                    params: {
                        today: moment().tz(moment.tz.guess()).utc().format('YYYY-MM-DD'),
                        ...listQueryParams
                    },
                    paramsSerializer: ((listQueryParams) => qs.stringify(listQueryParams)),
                })
                .then((response) => {
                    const {data, meta} = response;
                    this.lists.latestJobApplications.dataSource = data;
                    this.lists.latestJobApplications.pagination = {...meta, current: meta.current_page};
                })
                .catch(() => this.lists.latestJobApplications.dataSource = [] )
                .finally(() => {this.lists.latestJobApplications.loading = false;})
            },
            handleExpireTableChange(pagination, filters, sorter) {
                const pager = { ...this.lists.expireJobs.pagination };
                pager.current = pagination.current;
                this.pagination = pager;
                const listQueryParams = {
                    page: pagination.current,
                    pageSize: (pagination.pageSize ? pagination.pageSize : 10),
                    sortField: sorter.field,
                    sortOrder: (sorter.order === 'descend' ? 'desc' : 'asc'),
                    ...filters
                };

                this.lists.expireJobs.listQueryParams = listQueryParams;
                this.getJobThatExpiresToday(listQueryParams);
            },
            handleJobAppTableChange(pagination, filters, sorter) {
                const pager = { ...this.lists.latestJobApplications.pagination };
                pager.current = pagination.current;
                this.pagination = pager;
                const listQueryParams = {
                    page: pagination.current,
                    pageSize: (pagination.pageSize ? pagination.pageSize : 10),
                    sortField: sorter.field,
                    sortOrder: (sorter.order === 'descend' ? 'desc' : 'asc'),
                    ...filters
                };

                this.lists.latestJobApplications.listQueryParams = listQueryParams;
                this.getJobThatExpiresToday(listQueryParams);
            },
            getCompanyJobRevenue() {
                this.charts.companyJobs.loading = true;

                request({
                    url: `/dashboard/company/job/revenue`,
                    params: {
                        l_from_date: this.companyJobRevenueFrom,
                        l_to_date: this.companyJobRevenueTo,
                    }
                })
                    .then((response) => {
                        const data = [];
                        const companies = [];
                        response.data.map((item) => {
                            companies.push(item.name);
                            data.push({
                                y: item.jobs_count,
                                name: item.name,
                            })
                        });
                        this.charts.companyJobs.series[0].data = data;
                        this.charts.companyJobs.xAxis.categories = companies;
                    })
                    .catch(() => {})
                    .finally(() => {this.charts.companyJobs.loading = false;})
            },
            getMonthlyJobApplication() {
                this.charts.monthlyJobApplications.loading = true;

                request({
                    url: `/dashboard/job/application/monthly/revenue`,
                })
                    .then((response) => {
                        const data = [];
                        response.data.map((item, index) => {
                            data.push({
                                y: item,
                                name: this.charts.monthlyJobApplications.xAxis.categories[index],
                            })
                        });
                        this.charts.monthlyJobApplications.series[0].data = data;
                    })
                    .catch(() => {})
                    .finally(() => {this.charts.monthlyJobApplications.loading = false;})
            },
            covertData(date, toFormat = 'DD-MM-YYYY hh:mm A', fromFormat = 'YYYY-MM-DD HH:mm:ss') {
                return utcDateToLocalDate(date, toFormat, fromFormat)
            },
            expireJobRange(date, dateString) {
                this.companyJobRevenueFrom = (dateString[0] !== "" ? dateToUtcDate(dateString[0], 'YYYY-MM-DD', 'YYYY-MM-DD') : '');
                this.companyJobRevenueTo = (dateString[1] !== "" ? dateToUtcDate(dateString[1], 'YYYY-MM-DD', 'YYYY-MM-DD') : '');
            }
        },
        watch: {
            companyJobRevenueFrom: function (newVal) {
                this.getCompanyJobRevenue()
            }
        }
    }
</script>

<style lang="scss">
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
