<template>
    <b-row>
        <b-col cols="12">
            <b-row class="align-items-center job-header-simple">
                <b-col cols="12" md="6">
                    <h2 class="job-header-title">
                        Job Management
                    </h2>
                </b-col>

                <b-col cols="12" md="6" class="text-md-right mt-15 mt-md-0">
                    <b-button variant="primary" class="mr-10" @click="handleFormClick" title="Add New Job">
                        <i class="bi bi-plus-circle mr-5"></i>
                        New Job
                    </b-button>
                    <b-button variant="outline-secondary" class="mr-10" @click="handleFilterClick" title="Advance Filter">
                        <i class="bi bi-funnel mr-5"></i>
                        Filter
                    </b-button>
                    <b-dropdown right variant="outline-secondary" title="Export Data" text="Export">
                        <b-dropdown-item @click="handleCsvExportClick">Export CSV</b-dropdown-item>
                        <b-dropdown-item @click="handlePdfExportClick">Export PDF</b-dropdown-item>
                    </b-dropdown>
                </b-col>
            </b-row>
            <div class="mt-20">
                <b-sidebar v-model="filterVisible" title="Filter Panel" shadow backdrop no-header-close>
                    <form @submit.prevent="handleSearch" autocomplete="off" class="p-3">
                        <b-form-group label="Title" class="mb-10">
                            <b-form-input v-model="filters.title"></b-form-input>
                        </b-form-group>
                        <b-form-group label="Company" class="mb-10">
                            <v-select
                                v-model="filters.company_id"
                                :options="dropdowns.companies"
                                label="label"
                                :reduce="company => company.id"
                                :selectable="company => !company.disabled"
                                placeholder="Select Company"
                            ></v-select>
                        </b-form-group>
                        <b-form-group label="Department" class="mb-10">
                            <v-select
                                v-model="filters.job_department_id"
                                :options="dropdowns.departments"
                                label="label"
                                :reduce="department => department.id"
                                :selectable="department => !department.disabled"
                                placeholder="Select Department"
                            ></v-select>
                        </b-form-group>
                        <b-form-group label="is Active?" class="mb-10">
                            <v-select
                                v-model="filters.status"
                                :options="statusOptions"
                                label="text"
                                :reduce="option => option.value"
                                placeholder="Select Status"
                            ></v-select>
                        </b-form-group>
                        <b-form-group class="mb-10">
                            <b-form-checkbox v-model="filters.unarchived_at">Show Only UnArchived</b-form-checkbox>
                        </b-form-group>
                        <b-form-group class="mb-10">
                            <b-form-checkbox v-model="filters.expired">Show Only Expired</b-form-checkbox>
                        </b-form-group>
                        <div class="filter-footer text-right">
                            <b-button variant="primary" type="submit" class="mr-5">Filter</b-button>
                            <b-button @click="handleFilterClose">Cancel</b-button>
                        </div>
                    </form>
                </b-sidebar>

                <b-sidebar
                    v-model="formVisible"
                    :title="formTitle"
                    right
                    shadow
                    backdrop
                    no-header-close
                    width="760px"
                    sidebar-class="job-form-sidebar"
                    body-class="job-form-sidebar-body"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <b-form-group
                            label="Title *"
                            class="mb-10 required-input"
                            :state="invalidState('title')"
                            :invalid-feedback="formErrors.first('title')"
                        >
                            <b-form-input v-model="formFields.title"></b-form-input>
                        </b-form-group>
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Department *"
                                    class="mb-10 required-input"
                                    :state="invalidState('job_department_id')"
                                    :invalid-feedback="formErrors.first('job_department_id')"
                                >
                                    <v-select
                                        v-model="formFields.job_department_id"
                                        :options="dropdowns.departments"
                                        label="label"
                                        :reduce="department => department.id"
                                        :selectable="department => !department.disabled"
                                        placeholder="Select Department"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Company *"
                                    class="mb-10 required-input"
                                    :state="invalidState('company_id')"
                                    :invalid-feedback="formErrors.first('company_id')"
                                >
                                    <v-select
                                        v-model="formFields.company_id"
                                        :options="dropdowns.companies"
                                        label="label"
                                        :reduce="company => company.id"
                                        :selectable="company => !company.disabled"
                                        placeholder="Select Company"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Open Vacancies *"
                                    class="mb-10 required-input"
                                    :state="invalidState('open_vacancies')"
                                    :invalid-feedback="formErrors.first('open_vacancies')"
                                >
                                    <b-form-input v-model="formFields.open_vacancies" type="number"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Is active?" class="mb-10">
                                    <b-form-checkbox v-model="formFields.status">Yes</b-form-checkbox>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="From Date *"
                                    class="mb-10 required-input"
                                    :state="invalidState('from_date')"
                                    :invalid-feedback="formErrors.first('from_date')"
                                >
                                    <date-picker v-model="formFields.from_date" format="DD-MM-YYYY" value-type="YYYY-MM-DD" placeholder="" />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="To Date *"
                                    class="mb-10 required-input"
                                    :state="invalidState('to_date')"
                                    :invalid-feedback="formErrors.first('to_date')"
                                >
                                    <date-picker v-model="formFields.to_date" format="DD-MM-YYYY" value-type="YYYY-MM-DD" placeholder="" />
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group label="Qualifications" class="mb-10">
                                    <v-select
                                        v-model="formFields.job_qualifications"
                                        :options="dropdowns.qualifications"
                                        label="label"
                                        :reduce="qualification => qualification.id"
                                        :selectable="qualification => !qualification.disabled"
                                        multiple
                                        placeholder="Select Qualifications"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Experience Levels" class="mb-10">
                                    <v-select
                                        v-model="formFields.job_experience_levels"
                                        :options="dropdowns.experience_levels"
                                        label="label"
                                        :reduce="level => level.id"
                                        :selectable="level => !level.disabled"
                                        multiple
                                        placeholder="Select Experience Levels"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-form-group
                            label="Job Description *"
                            class="mb-10 required-input"
                            :state="invalidState('job_description')"
                            :invalid-feedback="formErrors.first('job_description')"
                        >
                            <b-form-textarea v-model="formFields.job_description"></b-form-textarea>
                        </b-form-group>
                        <b-form-group
                            label="Company Description *"
                            class="mb-10 required-input"
                            :state="invalidState('company_description')"
                            :invalid-feedback="formErrors.first('company_description')"
                        >
                            <b-form-textarea v-model="formFields.company_description"></b-form-textarea>
                        </b-form-group>

                        <div class="filter-footer text-right">
                            <b-button variant="primary" type="submit" class="mr-5">Submit</b-button>
                            <b-button @click="handleFormClose">Cancel</b-button>
                        </div>
                    </form>
                </b-sidebar>

                <b-table
                    class="fit-table jobs-table job-modern-table"
                    responsive
                    hover
                    show-empty
                    empty-text="No data available"
                    no-local-sorting
                    :fields="columns"
                    :items="dataSource"
                    :busy="loading"
                    :sort-by.sync="tableSortBy"
                    :sort-desc.sync="tableSortDesc"
                    @sort-changed="handleSortChange"
                >
                    <template #cell(details)="row">
                        <b-button size="sm" variant="outline-primary" class="job-table-details-btn" @click="row.toggleDetails">
                            <i class="bi bi-eye mr-5"></i>
                            {{ row.detailsShowing ? 'Hide' : 'View' }}
                        </b-button>
                    </template>
                    <template #cell(title)="data">
                        <div class="job-title-cell">
                            <div class="job-title-text">{{data.item.title}}</div>
                            <div class="job-meta-line">
                                {{data.item.company}} <span v-if="data.item.department">- {{data.item.department}}</span>
                            </div>
                        </div>
                    </template>
                    <template #cell(open_vacancies)="data">
                        <span class="job-vacancy-pill">
                            {{data.item.open_vacancies || 0}} Open
                        </span>
                    </template>
                    <template #cell(action)="data">
                        <div class="job-action-buttons text-right">
                            <template v-if="!data.item.archived_at">
                                <b-button size="sm" variant="outline-primary" class="mr-5" title="Edit" @click="handleEditRecord(data.item.id)">
                                    <i class="bi bi-pencil-square mr-5"></i>
                                    Edit
                                </b-button>
                                <b-button size="sm" variant="outline-warning" title="Archive Job" @click="confirmArchiveRecord(data.item.id)">
                                    <i class="bi bi-archive mr-5"></i>
                                    Archive
                                </b-button>
                            </template>
                            <template v-if="data.item.archived_at">
                                <b-badge variant="secondary">Archived</b-badge>
                            </template>
                        </div>
                    </template>
                    <template #cell(status)="data">
                        <div class="text-center">
                            <b-badge v-if="data.item.status !== 1" class="job-status-badge job-status-inactive">Inactive</b-badge>
                            <b-badge v-if="data.item.status === 1" class="job-status-badge job-status-active">Active</b-badge>
                        </div>
                    </template>
                    <template #row-details="row">
                        <div class="job-detail-panel">
                            <b-row>
                                <b-col cols="12" md="6">
                                    <p><strong>From Date:</strong> {{(row.item.from_date ? covertData(row.item.from_date, 'DD-MM-YYYY', 'YYYY-MM-DD') : '')}}</p>
                                    <p><strong>To Date:</strong> {{(row.item.to_date ? covertData(row.item.to_date, 'DD-MM-YYYY', 'YYYY-MM-DD') : '')}}</p>
                                    <p><strong>Experience Levels:</strong> {{row.item.experience_levels.map((item) => item.title).join(", ")}}</p>
                                    <p><strong>Qualification:</strong> {{row.item.qualifications.map((item) => item.title).join(", ")}}</p>
                                </b-col>
                                <b-col cols="12" md="6">
                                    <p><strong>Job Description:</strong> {{row.item.job_description}}</p>
                                    <p><strong>Company Description:</strong> {{row.item.company_description}}</p>
                                </b-col>
                            </b-row>
                        </div>
                    </template>
                </b-table>
                <b-pagination
                    v-if="pagination && pagination.total"
                    v-model="paginationCurrent"
                    :total-rows="pagination.total"
                    :per-page="pagination.per_page || pagination.pageSize || 10"
                    align="right"
                    class="mt-3"
                    @input="handlePageChange"
                ></b-pagination>
            </div>
        </b-col>
    </b-row>
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
                    total: 0,
                    per_page: 10,
                },
                tableSortBy: null,
                tableSortDesc: false,
                loading: false,
                columns: [
                    {
                        key: 'details',
                        label: '',
                    },
                    {
                        key: 'title',
                        label: 'Job',
                        sortable: true,
                    },
                    {
                        key: 'open_vacancies',
                        label: 'Open vacancies',
                        sortable: true,
                    },
                    {
                        key: 'status',
                        label: 'Is active?',
                        sortable: true,
                        thClass: 'text-center',
                        tdClass: 'text-center',
                    },
                    {
                        key: 'action',
                        label: 'Action',
                        thClass: 'text-right',
                        tdClass: 'text-right',
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
                },
                statusOptions: [
                    {value: null, text: ''},
                    {value: '1', text: 'Yes'},
                    {value: '0', text: 'No'},
                ],
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
            invalidState(field) {
                return this.formErrors.has(field) ? false : null;
            },
            handleSortChange(ctx) {
                this.handleTableChange(
                    {current: this.paginationCurrent, pageSize: this.pagination.per_page || 10},
                    {},
                    {field: ctx.sortBy, order: (ctx.sortDesc ? 'descend' : 'ascend')}
                );
            },
            handlePageChange(page) {
                this.handleTableChange(
                    {current: page, pageSize: this.pagination.per_page || 10},
                    {},
                    {field: this.tableSortBy, order: (this.tableSortDesc ? 'descend' : 'ascend')}
                );
            },
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
                listQueryParams = {
                    page: 1,
                    pageSize: 10,
                    ...listQueryParams,
                };

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
                    this.dataSource = data || [];
                    this.pagination = meta || {
                        page: 1,
                        total: this.dataSource.length,
                        per_page: 10,
                    };
                })
                .catch(() => {
                    this.dataSource = [];
                    this.pagination = {
                        page: 1,
                        total: 0,
                        per_page: 10,
                    };
                })
                .finally(() => this.loading = false);
            },
            handleSearch() {
                this.listQueryParams = {
                    ...this.listQueryParams,
                    page: 1,
                    filters: this.filters,
                };
                this.paginationCurrent = 1;
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
                        from_date: response.data.from_date ? moment(response.data.from_date).format('YYYY-MM-DD') : null,
                        to_date: response.data.to_date ? moment(response.data.to_date).format('YYYY-MM-DD') : null,
                        job_experience_levels: response.data.experience_levels.map((item) => item.id),
                        job_qualifications: response.data.qualifications.map((item) => item.id),
                    };
                    this.handleFormClick();
                })
                .catch((errors) => {
                    console.log(errors);
                })
            },
            confirmArchiveRecord(id) {
                if (window.confirm('Sure to Archive?')) {
                    this.handleArchiveRecord(id);
                }
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
            paginationCurrent: {
                get() {
                    return this.pagination.current_page || this.pagination.current || this.pagination.page || 1;
                },
                set(value) {
                    this.pagination = {...this.pagination, current: value, current_page: value};
                }
            }
        }
    }
</script>
<style lang="scss">
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
