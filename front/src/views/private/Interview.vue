<template>
    <b-row>
        <b-col cols="12">
            <b-row class="align-items-center job-header-simple">
                <b-col cols="12" md="6">
                    <h2 class="job-header-title">
                        Interviews
                    </h2>
                </b-col>

                <b-col cols="12" md="6" class="text-md-right mt-15 mt-md-0">
                    <b-button variant="primary" class="mr-10" @click="handleFormClick" title="Add New Interview">
                        <i class="bi bi-plus-circle mr-5"></i>
                        New Interview
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
                        <b-form-group label="Candidate" class="mb-10">
                            <v-select
                                v-model="filters.candidate_id"
                                :options="dropdowns.users"
                                label="label"
                                :reduce="user => user.id"
                                placeholder="Select Candidate"
                            ></v-select>
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
                        <b-form-group label="Interview Stage" class="mb-10">
                            <v-select
                                v-model="filters.interview_stage_id"
                                :options="dropdowns.interview_stages"
                                label="label"
                                :reduce="stage => stage.id"
                                placeholder="Select Interview Stage"
                            ></v-select>
                        </b-form-group>
                        <b-form-group label="Job Application" class="mb-10">
                            <v-select
                                v-model="filters.job_application_id"
                                :options="dropdowns.job_applications"
                                label="label"
                                :reduce="jobApplication => jobApplication.id"
                                placeholder="Select Job Application"
                            ></v-select>
                        </b-form-group>
                        <b-form-group label="Status" class="mb-10">
                            <v-select
                                v-model="filters.status"
                                :options="statusOptions"
                                label="text"
                                :reduce="option => option.value"
                                placeholder="Select Status"
                            ></v-select>
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
                    sidebar-class="interview-form-sidebar"
                    body-class="interview-form-sidebar-body"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group label="Status *" class="mb-10 required-input" :state="invalidState('status')" :invalid-feedback="formErrors.first('status')">
                                    <v-select
                                        v-model="formFields.status"
                                        :options="statusOptions"
                                        label="text"
                                        :reduce="option => option.value"
                                        placeholder="Select Status"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Interviewer Name" class="mb-10" :state="invalidState('interviewer_name')" :invalid-feedback="formErrors.first('interviewer_name')">
                                    <b-form-input v-model="formFields.interviewer_name"></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-form-group label="Candidate *" class="mb-10 required-input" :state="invalidState('candidate_id')" :invalid-feedback="formErrors.first('candidate_id')">
                            <v-select
                                v-model="formFields.candidate_id"
                                :options="dropdowns.users"
                                label="label"
                                :reduce="user => user.id"
                                placeholder="Select Candidate"
                            ></v-select>
                        </b-form-group>
                        <b-form-group label="Company *" class="mb-10 required-input" :state="invalidState('company_id')" :invalid-feedback="formErrors.first('company_id')">
                            <v-select
                                v-model="formFields.company_id"
                                :options="dropdowns.companies"
                                label="label"
                                :reduce="company => company.id"
                                :selectable="company => !company.disabled"
                                placeholder="Select Company"
                            ></v-select>
                        </b-form-group>
                        <b-form-group
                            v-if="formFields.status === '2'"
                            label="Interview Stage *"
                            class="mb-10 required-input"
                            :state="invalidState('interview_stage_id')"
                            :invalid-feedback="formErrors.first('interview_stage_id')"
                        >
                            <v-select
                                v-model="formFields.interview_stage_id"
                                :options="dropdowns.interview_stages"
                                label="label"
                                :reduce="stage => stage.id"
                                placeholder="Select Interview Stage"
                            ></v-select>
                        </b-form-group>
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group :label="formFields.status === '2' ? 'Interview Date *' : 'Interview Date'" class="mb-10" :class="{'required-input': formFields.status === '2'}" :state="invalidState('interview_date')" :invalid-feedback="formErrors.first('interview_date')">
                                    <date-picker v-model="formFields.interview_date" format="DD-MM-YYYY" value-type="YYYY-MM-DD" placeholder="" />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" v-if="formFields.status === '5'">
                                <b-form-group label="Joining Date" class="mb-10" :state="invalidState('joining_date')" :invalid-feedback="formErrors.first('joining_date')">
                                    <date-picker v-model="formFields.joining_date" format="DD-MM-YYYY" value-type="YYYY-MM-DD" />
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-form-group v-if="formFields.status === '5'" label="Salary" class="mb-10" :state="invalidState('salary')" :invalid-feedback="formErrors.first('salary')">
                            <b-form-input v-model="formFields.salary"></b-form-input>
                        </b-form-group>
                        <b-form-group label="Note" class="mb-10" :state="invalidState('notes')" :invalid-feedback="formErrors.first('notes')">
                            <b-form-textarea v-model="formFields.notes"></b-form-textarea>
                        </b-form-group>
                        <div class="filter-footer text-right">
                            <b-button variant="primary" type="submit" class="mr-5">Submit</b-button>
                            <b-button @click="handleFormClose">Cancel</b-button>
                        </div>
                    </form>
                </b-sidebar>

                <b-table
                    class="fit-table interviews-table interview-modern-table"
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
                    <template #cell(candidate_id)="data">
                        <div class="job-title-cell">
                            <div class="job-title-text">{{data.item.candidate}}</div>
                            <div class="job-meta-line">
                                {{data.item.job_application_number}}
                            </div>
                        </div>
                    </template>
                    <template #cell(company_id)="data">
                        <span class="interview-table-text">{{data.item.company}}</span>
                    </template>
                    <template #cell(interview_stage_id)="data">
                        <div class="job-title-cell">
                            <div class="job-title-text">{{data.item.interview_stage || 'Not Set'}}</div>
                            <div class="job-meta-line">
                                {{(data.item.interview_date) ? momentFormatter(data.item.interview_date).format('DD-MM-YYYY') : 'No interview date'}}
                            </div>
                        </div>
                    </template>
                    <template #cell(status)="data">
                        <b-badge :class="interviewStatusClass(data.item.status)">
                            {{data.item._status_}}
                        </b-badge>
                    </template>
                    <template #cell(action)="data">
                        <div class="job-action-buttons text-right">
                            <b-button
                                size="sm"
                                variant="outline-primary"
                                class="mr-5"
                                title="Edit"
                                @click="handleEditRecord(data.item.id)"
                                v-if="!data.item.hired_at && !data.item.rejected_at"
                            >
                                <i class="bi bi-pencil-square mr-5"></i>
                                Edit
                            </b-button>
                            <b-button
                                size="sm"
                                variant="outline-danger"
                                title="Delete"
                                v-if="!data.item.hired_at && !data.item.rejected_at"
                                @click="confirmDeleteRecord(data.item.id)"
                            >
                                <i class="bi bi-trash mr-5"></i>
                                Delete
                            </b-button>
                        </div>
                    </template>
                    <template #row-details="row">
                        <div class="job-detail-panel">
                            <b-row>
                                <b-col cols="12" md="6">
                                    <p><strong>Interviewer Name:</strong> {{row.item.interviewer_name}}</p>
                                    <p><strong>Interview Date:</strong> {{(row.item.interview_date) ? momentFormatter(row.item.interview_date).format('DD-MM-YYYY') : null}}</p>
                                    <p><strong>Joining Date:</strong> {{(row.item.joining_date) ? momentFormatter(row.item.joining_date).format('DD-MM-YYYY') : null}}</p>
                                    <p><strong>Salary:</strong> {{row.item.salary}}</p>
                                    <p><strong>Notes:</strong> {{row.item.notes}}</p>
                                </b-col>
                                <b-col cols="12" md="6">
                                    <div v-if="row.item.interview_stage_history.length">
                                        <p><strong>Stage History:</strong></p>
                                        <ul class="pl-3">
                                            <li v-for="(ish, index) in row.item.interview_stage_history" :key="index">
                                                {{momentFormatter(ish.created_at).format('DD-MM-YYYY')}} {{ish.interview_stage}}
                                            </li>
                                        </ul>
                                    </div>
                                    <p v-else><strong>Stage History:</strong> No history</p>
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
    import qs from "qs";
    import Error from "../../util/Error";
    import {handleServerError} from "../../util/utils";
    import {
        interviewCreated, interviewDeleted,
        interviewUpdated,
        serverError,
    } from "../../util/notify";
    import moment from "moment-timezone";

    const DEFAULT_FORM_STATE = {
        id: null,
        candidate_id: null,
        company_id: null,
        status: null,
        salary: null,
        notes: null,
        interviewer_name: null,
        interview_stage_id: null,
        interview_date: null,
        joining_date: null,
    };

    export default {
        mixins: [mixins],
        data() {
            return {
                formTitle: 'Add Interview',
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
                        key: 'candidate_id',
                        label: 'Candidate',
                        sortable: true,
                    },
                    {
                        key: 'company_id',
                        label: 'Company',
                        sortable: true,
                    },
                    {
                        key: 'interview_stage_id',
                        label: 'Current Stage',
                        sortable: true,
                    },
                    {
                        key: 'status',
                        label: 'Status',
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
                    candidate_id: undefined,
                    company_id: undefined,
                    interview_stage_id: undefined,
                    job_application_id: undefined,
                    status: null,
                },
                formVisible: false,
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({}),
                dropdowns: {
                    job_applications: [],
                    interview_stages: [],
                    companies: [],
                    users: [],
                },
                statusOptions: [
                    {value: '1', text: 'Pending'},
                    {value: '2', text: 'Interview Scheduled'},
                    {value: '3', text: 'On Hold'},
                    {value: '4', text: 'Rejected'},
                    {value: '5', text: 'Hired'},
                ],
            }
        },
        mounted() {
            this.loadList({ page: 1 });
            this.getUsers();
            this.getInterviewStages();
            this.getCompanies();
            this.getJobApplication();
        },
        methods: {
            invalidState(field) {
                return this.formErrors.has(field) ? false : null;
            },
            interviewStatusClass(status) {
                if (status === 1) {
                    return 'job-application-status-badge job-application-status-pending';
                }

                if (status === 2) {
                    return 'job-application-status-badge job-application-status-scheduled';
                }

                if (status === 4) {
                    return 'job-application-status-badge job-application-status-rejected';
                }

                if (status === 5) {
                    return 'job-application-status-badge job-application-status-hired';
                }

                return 'job-application-status-badge job-application-status-hold';
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
                    url: `interviews`,
                    params: listQueryParams,
                    paramsSerializer: ((listQueryParams) => qs.stringify(listQueryParams)),
                })
                .then((response) => {
                    const {data, meta} = response;
                    this.dataSource = data;
                    this.pagination = meta || {
                        page: 1,
                        total: data.length,
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
                this.formTitle = 'Add Interview';
                this.formErrors = new Error({});
                this.formFields = {...DEFAULT_FORM_STATE};
            },
            handleFormSubmit() {
                request({
                    method: "post",
                    url: this.formFields.id ? `interviews/update` : `interviews/create`,
                    data: {
                        ...this.formFields,
                        interview_date: ((this.formFields.interview_date) ? moment(this.formFields.interview_date).format('YYYY-MM-DD') : null),
                        joining_date: ((this.formFields.joining_date) ? moment(this.formFields.joining_date).format('YYYY-MM-DD') : null)
                    },
                })
                .then((response) => {
                    this.loadList(this.listQueryParams);

                    if (this.formFields.id) {
                        interviewUpdated();
                    } else {
                        interviewCreated();
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
                    url: `/interviews/detail/${id}`,
                })
                .then((response) => {
                    this.formTitle = 'Edit Interview';
                    this.formFields = {
                        ...response.data,
                        status: response.data.status.toString(),
                        interview_date: (response.data.interview_date) ? moment(response.data.interview_date).format('YYYY-MM-DD') : null,
                        joining_date: (response.data.joining_date) ? moment(response.data.joining_date).format('YYYY-MM-DD') : null,
                    };
                    this.handleFormClick();
                })
                .catch((errors) => {

                })
            },
            confirmDeleteRecord(id) {
                if (window.confirm('Sure to delete?')) {
                    this.handleDeleteRecord(id);
                }
            },
            handleDeleteRecord(id) {
                request({
                    method: "post",
                    url: `/interviews/delete`,
                    data: {
                        id: id
                    }
                })
                .then((response) => {
                    interviewDeleted();
                    this.loadList(this.listQueryParams);
                })
                .catch((errors) => {
                    serverError();
                })
            },
            handleCsvExportClick() {
                this.csv.config.filename = 'interviews';
                this.csv.config.headers = ['Candidate', 'Company', 'Current Stage', 'Job Application No', 'Status', 'Interviewer Name', 'Interviewer Date', 'Notes'];
                this.csv.dataSource = this.dataSource.map((item) => this.getExportKeys(item));
                this.exportCsv();
            },
            handlePdfExportClick() {
                this.pdf.title = 'All Interviews';
                this.pdf.filename = 'interviews';
                this.pdf.body = this.dataSource.map((item, index) => {
                    return {
                        index: (index + 1),
                        ...this.getExportKeys(item)
                    }
                });
                this.pdf.columns = [
                    {header: '#', dataKey: 'index'},
                    {header: 'Candidate', dataKey: 'candidate'},
                    {header: 'Current Stage', dataKey: 'current_stage'},
                    {header: 'Job Application Number', dataKey: 'job_application_number'},
                    {header: 'Status', dataKey: 'status'},
                    {header: 'Interviewer Name', dataKey: 'interviewer_name'},
                    {header: 'Interviewer Date', dataKey: 'interview_date'},
                    {header: 'Note', dataKey: 'note'},
                ];
                this.exportPdf();
            },
            getExportKeys(item) {
                return {
                    candidate: item.candidate,
                    company: item.company,
                    current_stage: ((item.interview_stage) ? item.interview_stage : ''),
                    job_application_number: ((item.job_application_number) ? item.job_application_number : ''),
                    status: item._status_,
                    interviewer_name: item.interviewer_name,
                    interview_date: ((item.interview_date) ? moment(item.interview_date).format('DD-MM-YYYY') : null),
                    note: item.notes,
                };
            },
            getInterviewStages() {
                this.dropdowns.interview_stages = [];
                const listQueryParams = {
                    sortField: 'i_order',
                    sortOrder: 'asc',
                };
                request({
                    method: "GET",
                    url: `interview/stages`,
                    params: listQueryParams,
                    paramsSerializer: ((listQueryParams) => qs.stringify(listQueryParams)),
                })
                    .then((response) => {
                        this.dropdowns.interview_stages = response.data.map((item) => {
                            return {
                                id: item.id,
                                label: `${item.title} (${item.i_order})`,
                                disabled: !item.status
                            }
                        })
                    })
                    .catch(() => {

                    });
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
                                label: `${item.name}`,
                                disabled: !item.status
                            }
                        })
                    })
                    .catch((errors) => {

                    });
            },
            getUsers() {
                this.dropdowns.users = [];
                request({
                    method: "GET",
                    url: `users`,
                })
                    .then((response) => {
                        this.dropdowns.users = response.data.filter((item) => item.type > 1).map((item) => {
                            return {
                                id: item.id,
                                label: `${item.first_name} ${item.last_name}`,
                            }
                        })
                    })
                    .catch((errors) => {
                        console.log(errors)
                    });
            },
            getJobApplication() {
                this.dropdowns.job_applications = [];
                request({
                    method: "GET",
                    url: `job/applications`,
                })
                    .then((response) => {
                        this.dropdowns.job_applications = response.data.map((item) => {
                            return {
                                id: item.id,
                                label: item.job_application_number,
                            }
                        })
                    })
                    .catch((errors) => {

                    });
            },
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
        .fit-table.interviews-table td:before{ font-weight: bold; }
        .fit-table.interviews-table td:nth-of-type(1):before { content: "#"; }
        .fit-table.interviews-table td:nth-of-type(2):before { content: "Candidate"; }
        .fit-table.interviews-table td:nth-of-type(3):before { content: "Company"; }
        .fit-table.interviews-table td:nth-of-type(4):before { content: "Current Stage"; }
        .fit-table.interviews-table td:nth-of-type(5):before { content: "Job App. No"; }
        .fit-table.interviews-table td:nth-of-type(6):before { content: "Status"; }
        .fit-table.interviews-table td:nth-of-type(7):before { content: "Action"; }
    }
</style>
