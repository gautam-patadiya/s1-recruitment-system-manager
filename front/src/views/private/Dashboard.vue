<template>
    <div class="dashboard-page">
        <b-row class="align-items-center job-header-simple">
            <b-col cols="12" md="7">
                <h2 class="job-header-title">
                    Dashboard
                </h2>
            </b-col>
            <b-col cols="12" md="5" class="text-md-right mt-15 mt-md-0">
                <span class="dashboard-date-pill">
                    <i class="bi bi-calendar2-week mr-5"></i>
                    {{ todayLabel }}
                </span>
            </b-col>
        </b-row>

        <b-row>
            <b-col cols="12" md="6" xl="3" class="mb-20">
                <b-card class="dashboard-stat-card">
                    <b-skeleton v-if="infoBoxes.loading"></b-skeleton>
                    <div v-else>
                        <div class="dashboard-stat-icon dashboard-stat-blue">
                            <i class="bi bi-people"></i>
                        </div>
                        <span>Total Users</span>
                        <strong>{{infoBoxes.users}}</strong>
                        <small>Users</small>
                    </div>
                </b-card>
            </b-col>
            <b-col cols="12" md="6" xl="3" class="mb-20">
                <b-card class="dashboard-stat-card">
                    <b-skeleton v-if="infoBoxes.loading"></b-skeleton>
                    <div v-else>
                        <div class="dashboard-stat-icon dashboard-stat-green">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <span>Total Candidates</span>
                        <strong>{{infoBoxes.candidates}}</strong>
                        <small>Candidates</small>
                    </div>
                </b-card>
            </b-col>
            <b-col cols="12" md="6" xl="3" class="mb-20">
                <b-card class="dashboard-stat-card">
                    <b-skeleton v-if="infoBoxes.loading"></b-skeleton>
                    <div v-else>
                        <div class="dashboard-stat-icon dashboard-stat-purple">
                            <i class="bi bi-building"></i>
                        </div>
                        <span>Total Companies</span>
                        <strong>{{infoBoxes.companies}}</strong>
                        <small>Companies</small>
                    </div>
                </b-card>
            </b-col>
            <b-col cols="12" md="6" xl="3" class="mb-20">
                <b-card class="dashboard-stat-card">
                    <b-skeleton v-if="infoBoxes.loading"></b-skeleton>
                    <div v-else>
                        <div class="dashboard-stat-icon dashboard-stat-orange">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <span>Total Active Jobs</span>
                        <strong>{{infoBoxes.activeJobs}}</strong>
                        <small>Active Jobs</small>
                    </div>
                </b-card>
            </b-col>
        </b-row>

        <b-row>
            <b-col cols="12" xl="4" class="mb-20">
                <b-card class="dashboard-widget-card dashboard-health-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h3>Hiring Health</h3>
                            <p>Simple score from current hiring activity.</p>
                        </div>
                        <span class="dashboard-health-score">{{ hiringHealthScore }}%</span>
                    </div>

                    <b-progress :value="hiringHealthScore" max="100" height="8px" class="dashboard-health-progress"></b-progress>

                    <div class="dashboard-health-grid">
                        <div>
                            <span>Active Jobs</span>
                            <strong>{{infoBoxes.activeJobs}}</strong>
                        </div>
                        <div>
                            <span>Candidates</span>
                            <strong>{{infoBoxes.candidates}}</strong>
                        </div>
                    </div>
                </b-card>
            </b-col>

            <b-col cols="12" xl="4" class="mb-20">
                <b-card class="dashboard-widget-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h3>Application Pipeline</h3>
                            <p>Status summary from latest applications.</p>
                        </div>
                    </div>

                    <div class="dashboard-pipeline-list">
                        <div class="dashboard-pipeline-item" v-for="item in pipelineItems" :key="item.label">
                            <span :class="['dashboard-pipeline-dot', item.className]"></span>
                            <span>{{ item.label }}</span>
                            <strong>{{ item.count }}</strong>
                        </div>
                    </div>
                </b-card>
            </b-col>

            <b-col cols="12" xl="4" class="mb-20">
                <b-card class="dashboard-widget-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h3>Quick Actions</h3>
                            <p>Jump to common recruitment work.</p>
                        </div>
                    </div>

                    <div class="dashboard-action-grid">
                        <router-link
                            v-for="action in quickActions"
                            :key="action.label"
                            :to="{name: action.routeName}"
                            class="dashboard-action-link"
                        >
                            <i :class="action.icon"></i>
                            <span>{{ action.label }}</span>
                        </router-link>
                    </div>
                </b-card>
            </b-col>
        </b-row>

        <b-row>
            <b-col cols="12" xl="7" class="mb-20">
                <b-card class="dashboard-panel-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h3>Jobs That Expire Today</h3>
                            <p>Openings that need attention today.</p>
                        </div>
                        <span class="report-count-pill">{{lists.expireJobs.dataSource.length}}</span>
                    </div>

                    <b-skeleton-table v-if="lists.expireJobs.loading" :rows="5" :columns="4"></b-skeleton-table>
                    <b-table
                        v-else
                        class="fit-table dashboard-modern-table"
                        responsive
                        hover
                        show-empty
                        empty-text="No data available"
                        :items="lists.expireJobs.dataSource"
                        :fields="lists.expireJobs.fields"
                        :busy="lists.expireJobs.loading"
                    >
                        <template #cell(index)="data">
                            {{ getListIndex(data.index, lists.expireJobs.pagination) }}
                        </template>
                        <template #cell(title)="data">
                            <span class="job-title-text">{{data.item.title}}</span>
                        </template>
                        <template #cell(company)="data">
                            <span class="job-meta-line">{{data.item.company || '-'}}</span>
                        </template>
                        <template #cell(date)="data">
                            <span class="report-date-text">
                                {{ covertData(data.item.from_date, 'DD.MM', 'YYYY-MM-DD') }} - {{ covertData(data.item.to_date, 'DD.MM', 'YYYY-MM-DD') }}
                            </span>
                        </template>
                    </b-table>
                </b-card>
            </b-col>

            <b-col cols="12" xl="5" class="mb-20">
                <b-card class="dashboard-panel-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h3>Latest Applications</h3>
                            <p>Newest candidate activity.</p>
                        </div>
                        <span class="report-count-pill">{{lists.latestJobApplications.dataSource.length}}</span>
                    </div>

                    <b-skeleton-table v-if="lists.latestJobApplications.loading" :rows="5" :columns="4"></b-skeleton-table>
                    <b-table
                        v-else
                        class="fit-table dashboard-modern-table"
                        responsive
                        hover
                        show-empty
                        empty-text="No data available"
                        :items="lists.latestJobApplications.dataSource"
                        :fields="lists.latestJobApplications.fields"
                        :busy="lists.latestJobApplications.loading"
                    >
                        <template #cell(index)="data">
                            {{ getListIndex(data.index, lists.latestJobApplications.pagination) }}
                        </template>
                        <template #cell(job_application_number)="data">
                            <span class="job-application-number">{{data.item.job_application_number}}</span>
                        </template>
                        <template #cell(candidate_name)="data">
                            <span class="job-title-text">{{ candidateName(data.item) }}</span>
                        </template>
                        <template #cell(created_at)="data">
                            <span class="report-date-text">{{ covertData(data.item.created_at) }}</span>
                        </template>
                    </b-table>
                </b-card>
            </b-col>
        </b-row>

        <b-row>
            <b-col cols="12" xl="6" class="mb-20">
                <b-card class="dashboard-chart-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h3>Jobs By Company</h3>
                            <p>Filter by date range to review company-wise jobs.</p>
                        </div>
                        <date-picker
                            class="dashboard-date-picker"
                            v-model="companyJobDateRange"
                            range
                            format="YYYY-MM-DD"
                            value-type="format"
                            @change="expireJobRange"
                        />
                    </div>
                    <b-skeleton v-if="charts.companyJobs.loading" height="260px"></b-skeleton>
                    <chart v-else :options="charts.companyJobs"></chart>
                </b-card>
            </b-col>

            <b-col cols="12" xl="6" class="mb-20">
                <b-card class="dashboard-chart-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h3>Monthly Job Applications</h3>
                            <p>Applications received during the current year.</p>
                        </div>
                    </div>
                    <b-skeleton v-if="charts.monthlyJobApplications.loading" height="260px"></b-skeleton>
                    <chart v-else :options="charts.monthlyJobApplications"></chart>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import {request} from "../../util/request";
    import moment from "moment-timezone";
    import qs from "qs";
    import {dateToUtcDate, utcDateToLocalDate} from "../../util/utils";
    import {Chart} from 'highcharts-vue'
    // @ is an alias to /src

    export default {
        components: {
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
                        fields: [
                            {
                                key: 'index',
                                label: '#',
                            },
                            {
                                key: 'title',
                                label: 'Job',
                            },
                            {
                                key: 'company',
                                label: 'Company',
                            },
                            {
                                key: 'date',
                                label: 'Date',
                            }
                        ],
                        dataSource: [],
                        loading: false,
                        pagination: {
                            page: 1,
                            total: 0,
                            per_page: 10,
                        },
                        listQueryParams: {},
                    },
                    latestJobApplications: {
                        fields: [
                            {
                                key: 'index',
                                label: '#',
                            },
                            {
                                key: 'job_application_number',
                                label: 'Application',
                            },
                            {
                                key: 'candidate_name',
                                label: 'Candidate',
                            },
                            {
                                key: 'created_at',
                                label: 'Applied At',
                            },
                        ],
                        dataSource: [],
                        loading: false,
                        pagination: {
                            page: 1,
                            total: 0,
                            per_page: 10,
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
                        chart: {
                            type: 'areaspline',
                        },
                        title: { text: '' },
                        legend: {
                            enabled: false
                        },
                        xAxis: {
                            categories: []
                        },
                        yAxis: {
                            title: { text: '' }
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
                        chart: {
                            type: 'column',
                        },
                        title: { text: '' },
                        legend: {
                            enabled: false
                        },
                        xAxis: {
                            categories: [
                                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
                            ]
                        },
                        yAxis: {
                            title: { text: '' }
                        },
                        series: [
                            {
                                name: 'Job App. By Month',
                                data: []
                            }
                        ]
                    }
                },
                companyJobDateRange: [],
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
                    this.lists.expireJobs.dataSource = data || [];
                    this.lists.expireJobs.pagination = {
                        page: 1,
                        total: this.lists.expireJobs.dataSource.length,
                        per_page: 10,
                        ...(meta || {}),
                        current: (meta ? meta.current_page : 1),
                    };
                })
                .catch(() => {
                    this.lists.expireJobs.dataSource = [];
                    this.lists.expireJobs.pagination = {
                        page: 1,
                        total: 0,
                        per_page: 10,
                    };
                })
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
                    this.lists.latestJobApplications.dataSource = data || [];
                    this.lists.latestJobApplications.pagination = {
                        page: 1,
                        total: this.lists.latestJobApplications.dataSource.length,
                        per_page: 10,
                        ...(meta || {}),
                        current: (meta ? meta.current_page : 1),
                    };
                })
                .catch(() => {
                    this.lists.latestJobApplications.dataSource = [];
                    this.lists.latestJobApplications.pagination = {
                        page: 1,
                        total: 0,
                        per_page: 10,
                    };
                })
                .finally(() => {this.lists.latestJobApplications.loading = false;})
            },
            handleExpireTableChange(pagination, filters, sorter) {
                const pager = { ...this.lists.expireJobs.pagination };
                pager.current = pagination.current;
                this.lists.expireJobs.pagination = pager;
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
                this.lists.latestJobApplications.pagination = pager;
                const listQueryParams = {
                    page: pagination.current,
                    pageSize: (pagination.pageSize ? pagination.pageSize : 10),
                    sortField: sorter.field,
                    sortOrder: (sorter.order === 'descend' ? 'desc' : 'asc'),
                    ...filters
                };

                this.lists.latestJobApplications.listQueryParams = listQueryParams;
                this.getJobApplication(listQueryParams);
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
                        (response.data || []).map((item) => {
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
                        (response.data || []).map((item, index) => {
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
                if (!date) {
                    return '-';
                }

                return utcDateToLocalDate(date, toFormat, fromFormat)
            },
            candidateName(item) {
                if (!item.candidate) {
                    return '-';
                }

                const firstName = item.candidate.first_name || '';
                const lastName = item.candidate.last_name || '';
                const fullName = `${firstName} ${lastName}`.trim();

                return fullName || '-';
            },
            getListIndex(index, pagination) {
                pagination = pagination || {};

                const page = pagination.current_page || pagination.current || pagination.page || 1;
                const perPage = pagination.per_page || pagination.pageSize || 10;

                return index + ((page - 1) * perPage) + 1;
            },
            countApplicationsByStatus(status) {
                return this.lists.latestJobApplications.dataSource.filter((item) => {
                    return Number(item.status) === status;
                }).length;
            },
            expireJobRange(date) {
                const range = date || [];
                this.companyJobRevenueFrom = (range[0] ? dateToUtcDate(range[0], 'YYYY-MM-DD', 'YYYY-MM-DD') : '');
                this.companyJobRevenueTo = (range[1] ? dateToUtcDate(range[1], 'YYYY-MM-DD', 'YYYY-MM-DD') : '');
            }
        },
        computed: {
            todayLabel() {
                return moment().format('DD MMM YYYY');
            },
            hiringHealthScore() {
                const activeJobsScore = Math.min(this.infoBoxes.activeJobs * 10, 40);
                const candidatesScore = Math.min(this.infoBoxes.candidates * 3, 35);
                const companiesScore = Math.min(this.infoBoxes.companies * 2, 25);

                return activeJobsScore + candidatesScore + companiesScore;
            },
            quickActions() {
                return [
                    {label: 'Manage Jobs', routeName: 'job', icon: 'bi bi-briefcase'},
                    {label: 'Applications', routeName: 'jobApplication', icon: 'bi bi-card-checklist'},
                    {label: 'Interviews', routeName: 'interview', icon: 'bi bi-calendar2-check'},
                    {label: 'Reports', routeName: 'report.jobOverview', icon: 'bi bi-graph-up'},
                ];
            },
            pipelineItems() {
                return [
                    {
                        label: 'Pending',
                        count: this.countApplicationsByStatus(1),
                        className: 'dashboard-dot-pending',
                    },
                    {
                        label: 'Scheduled',
                        count: this.countApplicationsByStatus(2),
                        className: 'dashboard-dot-scheduled',
                    },
                    {
                        label: 'Hired',
                        count: this.countApplicationsByStatus(4),
                        className: 'dashboard-dot-hired',
                    },
                    {
                        label: 'On Hold',
                        count: this.countApplicationsByStatus(5),
                        className: 'dashboard-dot-hold',
                    },
                ];
            },
        },
        watch: {
            companyJobRevenueFrom: function (newVal) {
                this.getCompanyJobRevenue()
            },
            companyJobRevenueTo: function (newVal) {
                this.getCompanyJobRevenue()
            }
        }
    }
</script>

<style lang="scss">
    .dashboard-page {
        padding-bottom: 20px;
    }

    .dashboard-date-pill {
        align-items: center;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        color: #475569;
        display: inline-flex;
        font-size: 13px;
        font-weight: 700;
        padding: 10px 14px;
    }

    .dashboard-stat-card,
    .dashboard-widget-card,
    .dashboard-panel-card,
    .dashboard-chart-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
        height: 100%;
    }

    .dashboard-stat-card .card-body {
        padding: 20px;
    }

    .dashboard-stat-card span {
        color: #64748b;
        display: block;
        font-size: 13px;
        font-weight: 700;
        margin-top: 16px;
    }

    .dashboard-stat-card strong {
        color: #0f172a;
        display: block;
        font-size: 30px;
        font-weight: 800;
        line-height: 1;
        margin-top: 8px;
    }

    .dashboard-stat-card small {
        color: #475569;
        display: block;
        font-size: 13px;
        font-weight: 700;
        margin-top: 8px;
    }

    .dashboard-stat-icon {
        align-items: center;
        border-radius: 14px;
        display: inline-flex;
        height: 42px;
        justify-content: center;
        width: 42px;
    }

    .dashboard-widget-card .card-body {
        padding: 20px;
    }

    .dashboard-health-score {
        align-items: center;
        background: #eff6ff;
        border-radius: 999px;
        color: #2563eb;
        display: inline-flex;
        font-size: 13px;
        font-weight: 800;
        padding: 8px 12px;
    }

    .dashboard-health-progress {
        background: #e2e8f0;
        border-radius: 999px;
        margin-bottom: 18px;
        overflow: hidden;
    }

    .dashboard-health-progress .progress-bar {
        background: linear-gradient(90deg, #2563eb, #38bdf8);
    }

    .dashboard-health-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-health-grid div {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px;
    }

    .dashboard-health-grid span {
        color: #64748b;
        display: block;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .dashboard-health-grid strong {
        color: #0f172a;
        font-size: 22px;
        font-weight: 800;
    }

    .dashboard-pipeline-list {
        display: grid;
        gap: 10px;
    }

    .dashboard-pipeline-item {
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        color: #475569;
        display: grid;
        font-size: 13px;
        font-weight: 700;
        gap: 10px;
        grid-template-columns: auto 1fr auto;
        padding: 12px;
    }

    .dashboard-pipeline-item strong {
        color: #0f172a;
        font-size: 16px;
    }

    .dashboard-pipeline-dot {
        border-radius: 999px;
        display: inline-flex;
        height: 10px;
        width: 10px;
    }

    .dashboard-dot-pending {
        background: #f59e0b;
    }

    .dashboard-dot-scheduled {
        background: #2563eb;
    }

    .dashboard-dot-hired {
        background: #059669;
    }

    .dashboard-dot-hold {
        background: #64748b;
    }

    .dashboard-action-grid {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-action-link {
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        color: #0f172a;
        display: flex;
        font-size: 13px;
        font-weight: 800;
        gap: 10px;
        padding: 14px;
    }

    .dashboard-action-link:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
        text-decoration: none;
    }

    .dashboard-stat-blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .dashboard-stat-green {
        background: #ecfdf5;
        color: #059669;
    }

    .dashboard-stat-purple {
        background: #f5f3ff;
        color: #7c3aed;
    }

    .dashboard-stat-orange {
        background: #fff7ed;
        color: #ea580c;
    }

    .dashboard-card-header {
        align-items: flex-start;
        display: flex;
        gap: 15px;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .dashboard-card-header h3 {
        color: #0f172a;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .dashboard-card-header p {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 0;
    }

    .dashboard-modern-table {
        margin-bottom: 0;
    }

    .dashboard-date-picker {
        max-width: 280px;
        width: 100%;
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

        .dashboard-card-header {
            display: block;
        }

        .dashboard-card-header .mx-datepicker {
            margin-top: 12px;
            max-width: none;
            width: 100%;
        }
    }
</style>
