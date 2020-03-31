<template>
    <a-row>
        <a-col span="24">
            <div>
                <a-row>
                    <a-col :xs="24" :sm="24" :md="12">
                        <h2 class="mb-0">
                            All <strong>Jobs</strong>
                        </h2>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" class="text-right">
                        <a-button shape="round" class="mr-10" @click="handleFormClick" title="Add New Entry">New Entry</a-button>
                        <a-button shape="round" class="mr-10" icon="filter" @click="handleFilterClick" title="Advance filter" />
                        <a-dropdown>
                            <a href="javascript:;" title="Export data"><a-icon type="ellipsis" :style="{ fontSize: '20px', color: '#08c' }"/></a>
                            <a-menu slot="overlay">
                                <a-menu-item key="1" @click="handleCsvExportClick"> <a-icon type="file-excel" /> Export CSV </a-menu-item>
                                <a-menu-item key="2" @click="handlePdfExportClick"> <a-icon type="file-pdf" /> Export PDF </a-menu-item>
                            </a-menu>
                        </a-dropdown>
                    </a-col>
                </a-row>
            </div>
            <div class="mt-20">
                <a-drawer
                    title="Filter Panel"
                    placement="left"
                    :closable="false"
                    @close="handleFilterClose"
                    :visible="filterVisible"
                    :wrapStyle="{height: 'calc(100% - 108px)',overflow: 'auto', paddingBottom: '108px'}"
                >
                    <form @submit.prevent="handleSearch" autocomplete="off">
                        <a-row>
                            <a-col>
                                <a-form-item label="Title" class="mb-10">
                                    <a-input v-model="filters.title" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Company" class="mb-10">
                                    <a-select allowClear v-model="filters.company_id">
                                        <a-select-option v-for="(company, index) in dropdowns.companies"
                                                         :key="index"
                                                         :disabled="company.disabled" :value="company.id">
                                            {{company.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Department" class="mb-10">
                                    <a-select allowClear v-model="filters.job_department_id">
                                        <a-select-option v-for="(department, index) in dropdowns.departments"
                                                         :key="index"
                                                         :disabled="department.disabled" :value="department.id">
                                            {{department.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="is Active?" class="mb-10">
                                    <a-select allowClear v-model="filters.status">
                                        <a-select-option key="1" value="1">Yes</a-select-option>
                                        <a-select-option key="2" value="0">No</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item class="mb-10">
                                    <a-checkbox :checked="filters.unarchived_at" v-model="filters.unarchived_at">
                                        Show Only UnArchived
                                    </a-checkbox>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item class="mb-10">
                                    <a-checkbox :checked="filters.expired" v-model="filters.expired">
                                        Show Only Expired
                                    </a-checkbox>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <div class="filter-footer text-right">
                            <a-button type="primary" html-type="submit" class="mr-5">Filter</a-button>
                            <a-button @click="handleFilterClose">Cancel</a-button>
                        </div>
                    </form>
                </a-drawer>

                <a-drawer
                    width="600"
                    :title="formTitle"
                    placement="right"
                    :closable="false"
                    @close="handleFormClose"
                    :visible="formVisible"
                    :wrapStyle="{height: 'calc(100% - 108px)',overflow: 'auto', paddingBottom: '108px'}"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <a-row :gutter="16">
                            <a-col :md="24">
                                <a-form-item label="Title" class="mb-10 required-input"
                                 :validate-status="(formErrors.has('title') ? 'error' : '')"
                                 :help="formErrors.first('title')">
                                    <a-input v-model="formFields.title" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Department" class="mb-10 required-input"
                                         :validate-status="(formErrors.has('job_department_id') ? 'error' : '')"
                                         :help="formErrors.first('job_department_id')">
                                    <a-select allowClear v-model="formFields.job_department_id">
                                        <a-select-option v-for="(department, index) in dropdowns.departments"
                                                         :key="index"
                                                         :disabled="department.disabled" :value="department.id">
                                            {{department.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Company" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('company_id') ? 'error' : '')"
                                     :help="formErrors.first('company_id')">
                                    <a-select allowClear v-model="formFields.company_id">
                                        <a-select-option v-for="(company, index) in dropdowns.companies"
                                                         :key="index"
                                                         :disabled="company.disabled" :value="company.id">
                                            {{company.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Open Vacancies" class="mb-10 required-input"
                                             :validate-status="(formErrors.has('open_vacancies') ? 'error' : '')"
                                             :help="formErrors.first('open_vacancies')">
                                    <a-input v-model="formFields.open_vacancies" type="number" />
                                </a-form-item>
                            </a-col>

                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Is active?" class="mb-10 required-input">
                                    <a-checkbox :checked="formFields.status" v-model="formFields.status">
                                        Yes
                                    </a-checkbox>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="From Date" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('from_date') ? 'error' : '')"
                                     :help="formErrors.first('from_date')">
                                    <a-date-picker v-model="formFields.from_date" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="To Date" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('to_date') ? 'error' : '')"
                                     :help="formErrors.first('to_date')">
                                    <a-date-picker v-model="formFields.to_date" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Qualifications" class="mb-10">
                                    <a-select mode="multiple" v-model="formFields.job_qualifications">
                                        <a-select-option v-for="(qualification, index) in dropdowns.qualifications"
                                                         :key="index"
                                                         :disabled="qualification.disabled" :value="qualification.id">
                                            {{qualification.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Experience Levels" class="mb-10">
                                    <a-select mode="multiple" v-model="formFields.job_experience_levels">
                                        <a-select-option v-for="(experience_level, index) in dropdowns.experience_levels"
                                                         :key="index"
                                                         :disabled="experience_level.disabled" :value="experience_level.id">
                                            {{experience_level.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col span="24">
                                <a-form-item label="Job Description" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('job_description') ? 'error' : '')"
                                     :help="formErrors.first('job_description')">
                                    <a-textarea v-model="formFields.job_description" autosize />
                                </a-form-item>
                            </a-col>
                            <a-col span="24">
                                <a-form-item label="Company Description" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('company_description') ? 'error' : '')"
                                     :help="formErrors.first('company_description')">
                                    <a-textarea v-model="formFields.company_description" autosize />
                                </a-form-item>
                            </a-col>
                        </a-row>

                        <div class="filter-footer text-right">
                            <a-button type="primary" html-type="submit" class="mr-5">Submit</a-button>
                            <a-button @click="handleFormClose">Cancel</a-button>
                        </div>
                    </form>
                </a-drawer>

                <a-table
                    class="fit-table jobs-table"
                    :columns="columns"
                    :rowKey="record => record.id"
                    :dataSource="dataSource"
                    :pagination="pagination"
                    :loading="loading"
                    @change="handleTableChange">
                    <template slot="action" slot-scope="text, record, index" class="text-right">
                        <template v-if="!record.archived_at">
                            <a-button size="small" type="default" shape="circle" class="mr-5" title="Edit" @click="handleEditRecord(record.id)">
                                <a-icon type="edit" />
                            </a-button>
                            <a-popconfirm
                                placement="left"
                                title="Sure to Archive?"
                                @confirm="() => handleArchiveRecord(record.id)"
                            >
                                <a-button size="small" type="default" shape="circle" title="Archive Job">
                                    <a-icon type="dropbox" />
                                </a-button>
                            </a-popconfirm>
                        </template>
                        <template v-if="record.archived_at">
                            Archived
                        </template>
                    </template>
                    <template slot="company" slot-scope="text, record, index">
                        {{record.company}}
                    </template>
                    <template slot="department" slot-scope="text, record, index">
                        {{record.department}}
                    </template>
                    <template slot="status" slot-scope="text, record, index" class="text-center">
                        <a-tag v-if="record.status !== 1" color="red">No</a-tag>
                        <a-tag v-if="record.status === 1" color="blue">Yes</a-tag>
                    </template>
                    <div slot="expandedRowRender" slot-scope="record">
                        <p><strong>From Date:</strong> {{(record.from_date ? covertData(record.from_date, 'DD-MM-YYYY', 'YYYY-MM-DD') : '')}}</p>
                        <p><strong>To Date:</strong> {{(record.to_date ? covertData(record.to_date, 'DD-MM-YYYY', 'YYYY-MM-DD') : '')}}</p>
                        <p><strong>Experience Levels:</strong> {{record.experience_levels.map((item) => item.title).join(", ")}}</p>
                        <p><strong>Qualification:</strong> {{record.qualifications.map((item) => item.title).join(", ")}}</p>
                        <p><strong>Job Description:</strong> {{record.job_description}}</p>
                        <p><strong>Company Description:</strong> {{record.company_description}}</p>
                    </div>
                </a-table>
            </div>
        </a-col>
    </a-row>
</template>
<script>
    import {request} from "../../util/request";
    import mixins from "../../util/mixins";
    import moment from "moment-timezone";
    import qs from "qs";
    import Error from "../../util/Error";
    import {handleServerError, utcDateToLocalDate} from "../../util/utils";
    import {jobArchived, jobCreated, jobDeleted, jobUpdated, serverError} from "../../util/notify";

    const DEFAULT_FORM_STATE = {
        id: null,
        company_id: null,
        job_department_id: null,
        status: null,
        open_vacancies: null,
        title: null,

        from_date: null,
        to_date: null,
        job_description: null,
        company_description: null,
        job_qualifications: [],
        job_experience_levels: [],
    };

    export default {
        mixins: [mixins],
        data() {
            return {
                formTitle: 'Add Job',
                dataSource: [],
                pagination: {
                    page: 1,
                },
                loading: false,
                columns: [
                    {
                        title: 'Title',
                        dataIndex: 'title',
                        width: 150,
                        sorter: true,
                    },
                    {
                        title: 'Company',
                        dataIndex: 'company_id',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'company' },
                    },
                    {
                        title: 'Department',
                        dataIndex: 'job_department_id',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'department' },
                    },
                    {
                        title: 'Open vacancies',
                        dataIndex: 'open_vacancies',
                        width: 150,
                        sorter: true,
                    },
                    {
                        title: 'Is active?',
                        className: 'text-center',
                        dataIndex: 'status',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'status' },
                    },
                    {
                        title: 'Action',
                        className: 'text-right',
                        dataIndex: 'action',
                        width: 150,
                        scopedSlots: { customRender: 'action' },
                    }
                ],
                listQueryParams: {},
                filterVisible: false,
                filters: {
                    title: null,
                    company_id: null,
                    job_department_id: null,
                    status: null,
                    unarchived_at: true,
                    expired: false,
                    now: moment().format('YYYY-MM-DD'),
                },
                formVisible: false,
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({}),
                dropdowns: {
                    companies: [],
                    departments: [],
                    experience_levels: [],
                    qualifications: [],
                }
            }
        },
        mounted() {
            this.loadList({ page: 1, filters: {...this.filters}});
            this.getCompanies();
            this.getExperienceLevels();
            this.getJobDepartments();
            this.getQualifications();
        },
        methods: {
            handleTableChange(pagination, filters, sorter) {
                const pager = { ...this.pagination };
                pager.current = pagination.current;
                this.pagination = pager;
                const listQueryParams = {
                    page: pagination.current,
                    pageSize: (pagination.pageSize ? pagination.pageSize : 10),
                    sortField: sorter.field,
                    sortOrder: (sorter.order === 'descend' ? 'desc' : 'asc'),
                    filters: this.filters
                };
                this.listQueryParams = listQueryParams;
                this.loadList(listQueryParams);
            },
            loadList(listQueryParams) {
                this.loading = true;
                this.formErrors = new Error({});
                request({
                    method: "get",
                    url: `jobs`,
                    params: listQueryParams,
                    paramsSerializer: ((listQueryParams) => qs.stringify(listQueryParams)),
                })
                .then((response) => {
                    const {data, meta} = response;
                    this.dataSource = data;
                    this.pagination = meta;
                })
                .catch(() => this.dataSource = [] )
                .finally(() => this.loading = false);
            },
            handleSearch() {
                this.listQueryParams = {...this.listQueryParams, filters: this.filters};
                this.loadList(this.listQueryParams);
            },
            handleFilterClick() {
                this.filterVisible = true;
            },
            handleFilterClose() {
                this.filterVisible = false;
            },
            handleFormClick() {
                this.formVisible = true;
            },
            handleFormClose() {
                this.formVisible = false;
                this.formTitle = 'Add Job';
                this.formErrors = new Error({});
                this.formFields = {...DEFAULT_FORM_STATE};
            },
            handleFormSubmit() {
                request({
                    method: "post",
                    url: this.formFields.id ? `jobs/update` : `jobs/create`,
                    data: {
                        ...this.formFields,
                        from_date: ((this.formFields.from_date) ? moment(this.formFields.from_date).format('YYYY-MM-DD') : null),
                        to_date: ((this.formFields.to_date) ? moment(this.formFields.to_date).format('YYYY-MM-DD') : null),
                    },
                })
                .then((response) => {
                    this.loadList(this.listQueryParams);

                    if (this.formFields.id) {
                        jobUpdated();
                    } else {
                        jobCreated();
                    }

                    this.handleFormClose();
                })
                .catch((errors) => {
                    if(errors.status && errors.status === 422) {
                        this.formErrors = new Error(errors.data.errors);
                    }

                    handleServerError(errors);
                });
            },
            handleEditRecord(id) {
                request({
                    method: "get",
                    url: `/jobs/detail/${id}`,
                })
                .then((response) => {
                    this.formTitle = 'Edit Job';
                    this.formFields = {
                        ...response.data,
                        status: Boolean(response.data.status),
                        from_date: moment(response.data.from_date),
                        to_date: moment(response.data.to_date),
                        job_experience_levels: response.data.experience_levels.map((item) => item.id),
                        job_qualifications: response.data.qualifications.map((item) => item.id),
                    };
                    this.handleFormClick();
                })
                .catch((errors) => {
                    console.log(errors);
                })
            },
            handleArchiveRecord(id) {
                request({
                    method: "post",
                    url: `/jobs/archive`,
                    data: {
                        id: id
                    }
                })
                .then((response) => {
                    jobArchived();
                    this.loadList(this.listQueryParams);
                })
                .catch((errors) => {
                    serverError();
                })
            },
            getCompanies() {
                this.dropdowns.companies = [];
                request({
                    method: "GET",
                    url: `companies`,
                })
                .then((response) => {
                    this.dropdowns.companies = response.data.map((item) => {
                        return {
                            id: item.id,
                            label: item.name,
                            disabled: !item.status
                        }
                    })
                })
                .catch(() => {

                });
            },
            getExperienceLevels() {
                this.dropdowns.experience_levels = [];
                request({
                    method: "GET",
                    url: `experience/levels`,
                })
                .then((response) => {
                    this.dropdowns.experience_levels = response.data.map((item) => {
                        return {
                            id: item.id,
                            label: item.title
                        }
                    })
                })
                .catch(() => { });
            },
            getJobDepartments() {
                this.dropdowns.departments = [];
                request({
                    method: "GET",
                    url: `job/departments`,
                })
                .then((response) => {
                    this.dropdowns.departments = response.data.map((item) => {
                        return {
                            id: item.id,
                            label: item.name
                        }
                    })
                })
                .catch(() => { });
            },
            getQualifications() {
                this.dropdowns.qualifications = [];
                request({
                    method: "GET",
                    url: `qualifications`,
                })
                .then((response) => {
                    this.dropdowns.qualifications = response.data.map((item) => {
                        return {
                            id: item.id,
                            label: item.title
                        }
                    })
                })
                .catch(() => { });
            },
            covertData(date, toFormat = 'DD-MM-YYYY hh:mm A', fromFormat = 'YYYY-MM-DD HH:mm:ss') {
                return utcDateToLocalDate(date, toFormat, fromFormat)
            },
            handleCsvExportClick() {
                this.csv.config.filename = 'jobs';
                this.csv.config.headers = ['Title', 'Company', 'Department', 'Open Vacancies', 'is active?', 'From Date', 'To Date', 'Experience Levels', 'Qualification', 'Job Description', 'Company Description'];
                this.csv.dataSource = this.dataSource.map((item) => this.getExportKeys(item));
                this.exportCsv();
            },
            handlePdfExportClick() {
                this.pdf.title = 'All Jobs';
                this.pdf.filename = 'jobs';
                this.pdf.columns = [
                    {header: '#', dataKey: 'index'},
                    {header: 'Title', dataKey: 'title'},
                    {header: 'Company', dataKey: 'company'},
                    {header: 'Department', dataKey: 'department'},
                    {header: 'Open Vacancies', dataKey: 'open_vacancies'},
                    {header: 'is active?', dataKey: 'status'},
                    {header: 'From Date', dataKey: 'from_date'},
                    {header: 'To Date', dataKey: 'to_date'},
                    {header: 'Experience Levels', dataKey: 'experience_levels'},
                    {header: 'Qualification', dataKey: 'qualifications'},
                ];

                this.pdf.body = this.dataSource.map((item, index) => {
                    return {
                        index: (index + 1),
                        ...this.getExportKeys(item)
                    }
                });
                this.exportPdf();
            },
            getExportKeys(item) {
                return {
                    title: item.title,
                    company: item.company,
                    department: item.department,
                    open_vacancies: item.open_vacancies,
                    status: ((item.status !== 1) ? "No": "Yes"),
                    from_date: (item.from_date) ? moment(item.from_date).format('DD-MM-YYYY') : "",
                    to_date: (item.to_date) ? moment(item.to_date).format('DD-MM-YYYY') : "",
                    job_description: item.job_description,
                    company_description: item.company_description
                };
            }
        },
        computed: {

        }
    }
</script>
<style lang="scss">
    .ant-drawer-body {
        margin-bottom: 30px;
    }

    @media screen and (max-width: 790px) {
        .fit-table.jobs-table td:before{ font-weight: bold; }
        .fit-table.jobs-table td:nth-of-type(1):before { content: "#"; }
        .fit-table.jobs-table td:nth-of-type(2):before { content: "Title"; }
        .fit-table.jobs-table td:nth-of-type(3):before { content: "Company"; }
        .fit-table.jobs-table td:nth-of-type(4):before { content: "Department"; }
        .fit-table.jobs-table td:nth-of-type(5):before { content: "Open Vacancies"; }
        .fit-table.jobs-table td:nth-of-type(6):before { content: "Is Active?"; }
        .fit-table.jobs-table td:nth-of-type(7):before { content: "Action"; }
    }
</style>
