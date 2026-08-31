<template>
    <b-row>
        <b-col cols="12">
            <b-row class="align-items-center job-header-simple">
                <b-col cols="12" md="6">
                    <h2 class="job-header-title">
                        Job Applications
                    </h2>
                </b-col>

                <b-col cols="12" md="6" class="text-md-right mt-15 mt-md-0">
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
                        <b-form-group label="Status" class="mb-10">
                            <v-select
                                v-model="filters.status"
                                :options="statusOptions"
                                label="text"
                                :reduce="option => option.value"
                                placeholder="Select Status"
                            ></v-select>
                        </b-form-group>
                        <b-form-group label="Job Application Number" class="mb-10">
                            <b-form-input v-model="filters.job_application_number"></b-form-input>
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
                    sidebar-class="job-application-form-sidebar"
                    body-class="job-application-form-sidebar-body"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <b-form-group
                            label="Status *"
                            class="mb-10 required-input"
                            :state="invalidState('status')"
                            :invalid-feedback="formErrors.first('status')"
                        >
                            <v-select
                                v-model="formFields.status"
                                :options="formStatusOptions"
                                label="text"
                                :reduce="option => option.value"
                                placeholder="Select Status"
                            ></v-select>
                        </b-form-group>
                        <b-form-group
                            v-if="formFields.status === '2'"
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
                        <b-form-group
                            v-if="formFields.status === '2'"
                            label="Interview Date *"
                            class="mb-10 required-input"
                            :state="invalidState('interview_date')"
                            :invalid-feedback="formErrors.first('interview_date')"
                        >
                            <date-picker v-model="formFields.interview_date" format="DD-MM-YYYY" value-type="YYYY-MM-DD" placeholder="" />
                        </b-form-group>
                        <div v-if="formFields.status === '2'" class="mb-3">
                            Action will hide the Edit button and create Interview
                        </div>
                        <div class="filter-footer text-right">
                            <b-button variant="primary" type="submit" class="mr-5">Submit</b-button>
                            <b-button @click="handleFormClose">Cancel</b-button>
                        </div>
                    </form>
                </b-sidebar>

                <b-table
                    class="fit-table job-applications-table job-application-modern-table"
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
                    <template #cell(job_application_number)="data">
                        <div class="job-application-number-cell">
                            <div class="job-application-number">{{data.item.job_application_number}}</div>
                            <div class="job-application-date">
                                Applied {{covertData(data.item.created_at)}}
                            </div>
                        </div>
                    </template>
                    <template #cell(candidate_id)="data">
                        <div class="job-title-cell">
                            <div class="job-title-text">
                                {{data.item.candidate.first_name}} {{data.item.candidate.last_name}}
                            </div>
                            <div class="job-meta-line">
                                {{data.item.candidate.email}}
                            </div>
                        </div>
                    </template>
                    <template #cell(job_id)="data">
                        <div class="job-title-cell">
                            <div class="job-title-text">{{data.item.job.title}}</div>
                            <div class="job-meta-line">
                                {{data.item.job.company}} <span v-if="data.item.job.department">- {{data.item.job.department}}</span>
                            </div>
                        </div>
                    </template>
                    <template #cell(action)="data">
                        <div class="job-action-buttons text-right">
                            <b-button
                                size="sm"
                                variant="outline-primary"
                                class="mr-5"
                                title="Edit"
                                @click="handleEditRecord(data.item.id)"
                                v-if="data.item.status <= 1"
                            >
                                <i class="bi bi-pencil-square mr-5"></i>
                                Edit
                            </b-button>
                            <b-button
                                v-if="data.item.status > 1"
                                size="sm"
                                variant="outline-warning"
                                title="Make it Pending"
                                @click="confirmPendingAgain(data.item.id)"
                            >
                                <i class="bi bi-arrow-counterclockwise mr-5"></i>
                                Pending
                            </b-button>
                        </div>
                    </template>
                    <template #cell(status)="data">
                        <b-badge :class="applicationStatusClass(data.item.status)">
                            {{data.item._status_}}
                        </b-badge>
                    </template>
                    <template #row-details="row">
                        <div class="job-detail-panel">
                            <b-row>
                                <b-col cols="12" md="6">
                                    <p><strong>Company:</strong> {{row.item.job.company}}</p>
                                    <p><strong>Department:</strong> {{row.item.job.department}}</p>
                                </b-col>
                                <b-col cols="12" md="6">
                                    <p v-if="row.item.user_document">
                                        <strong>Uploaded Document:</strong>
                                        <a target="_blank" :href="row.item.user_document.download_url">
                                            {{row.item.user_document.document_type}} - {{row.item.user_document.filename}}
                                        </a>
                                    </p>
                                    <p v-else>
                                        <strong>Uploaded Document:</strong> Not uploaded
                                    </p>
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
    import moment from "moment";
    import qs from "qs";
    import Error from "../../util/Error";
    import {handleServerError, utcDateToLocalDate} from "../../util/utils";
    import {
        interviewStageCreated,
        interviewStageUpdated,
        jobApplicationCreated,
        jobApplicationUpdated
    } from "../../util/notify";

    const DEFAULT_FORM_STATE = {
        id: null,
        status: 1,
        job_id: null,
        interview_stage_id: null,
        interview_date: null,
        candidate_id: null,
        company_id: null,
    };

    export default {
        mixins: [mixins],
        data() {
            return {
                formTitle: 'Add Job Application',
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
                        key: 'job_application_number',
                        label: 'Application',
                        sortable: true,
                    },
                    {
                        key: 'candidate_id',
                        label: 'Candidate',
                        sortable: true,
                    },
                    {
                        key: 'job_id',
                        label: 'Job Title',
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
                    job_id: null,
                    status: null,
                    job_application_number: null,
                    candidate_id: null,
                    company_id: null,
                },
                formVisible: false,
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({}),
                dropdowns: {
                    interview_stages: [],
                    companies: [],
                    candidates: [],
                },
                statusOptions: [
                    {value: '1', text: 'Pending'},
                    {value: '2', text: 'Interview Scheduled'},
                    {value: '3', text: 'On Hold'},
                    {value: '4', text: 'Rejected'},
                    {value: '5', text: 'Hired'},
                ],
                formStatusOptions: [
                    {value: '1', text: 'Pending'},
                    {value: '2', text: 'Interview Scheduled'},
                ],
            }
        },
        mounted() {
            this.loadList({ page: 1 });
            this.getInterviewStages();
            this.getCompanies();
        },
        methods: {
            invalidState(field) {
                return this.formErrors.has(field) ? false : null;
            },
            applicationStatusClass(status) {
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
                    {current: this.paginationCurrent, pageSize: (this.pagination.per_page || 10)},
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
                    url: `job/applications`,
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
                this.formTitle = 'Add Job Application';
                this.formErrors = new Error({});
                this.formFields = {...DEFAULT_FORM_STATE};
            },
            handleFormSubmit() {
                request({
                    method: "post",
                    url: `job/applications/update`,
                    data: {
                        ...this.formFields,
                        interview_date: ((this.formFields.interview_date) ? moment(this.formFields.interview_date).format('YYYY-MM-DD') : null)
                    },
                })
                    .then((response) => {
                        this.loadList(this.listQueryParams);

                        if (this.formFields.id) {
                            jobApplicationUpdated();
                        } else {
                            jobApplicationCreated();
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
            confirmPendingAgain(id) {
                if (window.confirm('Sure to make it pending?')) {
                    this.handlePendingAgain(id);
                }
            },
            handlePendingAgain(id) {
                request({
                    method: "post",
                    url: `job/applications/update`,
                    data: {
                        id: id,
                        status: 1
                    },
                })
                    .then((response) => {
                        this.loadList(this.listQueryParams);

                        if (this.formFields.id) {
                            jobApplicationUpdated();
                        } else {
                            jobApplicationCreated();
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
                    url: `/job/applications/detail/${id}`,
                })
                    .then((response) => {
                        this.formTitle = 'Edit Job Applications';
                        this.formFields = {
                            status: response.data.status.toString(),
                            id: response.data.id,
                            company_id: response.data.company_id,
                            interview_stage_id: response.data.interview_stage_id,
                            interview_date: (response.data.interview_date) ? moment(response.data.interview_date).format('YYYY-MM-DD') : null,
                            candidate_id: response.data.candidate_id,
                        };
                        this.handleFormClick();
                    })
                    .catch((errors) => {

                    });
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
            covertData(date, toFormat = 'DD-MM-YYYY hh:mm A', fromFormat = 'YYYY-MM-DD HH:mm:ss') {
                return utcDateToLocalDate(date, toFormat, fromFormat)
            },
            handleCsvExportClick() {
                this.csv.config.filename = 'job_applications';
                this.csv.config.headers = ['Application No.', 'Candidate', 'Job Title', 'Company', 'Department', 'Status', 'Applied At'];
                this.csv.dataSource = this.dataSource.map((item) => this.getExportKeys(item));
                this.exportCsv();
            },
            handlePdfExportClick() {
                this.pdf.title = 'All Job Applications';
                this.pdf.filename = 'job_applications';
                this.pdf.body = this.dataSource.map((item, index) => {
                    return {
                        index: (index + 1),
                       ...this.getExportKeys(item)
                    }
                });
                this.pdf.columns = [
                    {header: '#', dataKey: 'index'},
                    {header: 'Application No.', dataKey: 'job_application_number'},
                    {header: 'Candidate', dataKey: 'candidate'},
                    {header: 'Title', dataKey: 'title'},
                    {header: 'Company', dataKey: 'company'},
                    {header: 'Department', dataKey: 'department'},
                    {header: 'Status', dataKey: 'status'},
                    {header: 'Applied At', dataKey: 'applied_at'},
                ];
                this.exportPdf();
            },
            getExportKeys(item) {
                return {
                    job_application_number: item.job_application_number,
                    candidate: `${item.candidate.first_name} ${item.candidate.last_name}`,
                    title: item.job.title,
                    company: item.job.company,
                    department: item.job.department,
                    status: item._status_,
                    applied_at: this.covertData(item.created_at),
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
        .fit-table.job-applications-table td:before{ font-weight: bold; }
        .fit-table.job-applications-table td:nth-of-type(1):before { content: "#"; }
        .fit-table.job-applications-table td:nth-of-type(2):before { content: "Number"; }
        .fit-table.job-applications-table td:nth-of-type(3):before { content: "Candidate"; }
        .fit-table.job-applications-table td:nth-of-type(4):before { content: "Job Title"; }
        .fit-table.job-applications-table td:nth-of-type(5):before { content: "Job Company"; }
        .fit-table.job-applications-table td:nth-of-type(6):before { content: "Status"; }
        .fit-table.job-applications-table td:nth-of-type(7):before { content: "Applied At"; }
        .fit-table.job-applications-table td:nth-of-type(8):before { content: "Action"; }
    }
</style>
