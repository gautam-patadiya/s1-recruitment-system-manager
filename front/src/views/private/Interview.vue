<template>
    <a-row>
        <a-col span="24">
            <div>
                <a-row>
                    <a-col :xs="24" :sm="24" :md="12">
                        <h2 class="mb-0">
                            All <strong>Interviews</strong>
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
                                <a-form-item label="Candidate" class="mb-10">
                                    <a-select allowClear v-model="filters.candidate_id">
                                        <a-select-option  v-for="(user, index) in dropdowns.users"
                                                         :key="index" :value="user.id">
                                            {{user.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Company" class="mb-10">
                                    <a-select allowClear v-model="filters.company_id">
                                        <a-select-option v-for="(company, index) in dropdowns.companies"
                                                         :key="index" :disabled="company.disabled" :value="company.id">
                                            {{company.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Interview Stage" class="mb-10">
                                    <a-select allowClear v-model="filters.interview_stage_id">
                                        <a-select-option v-for="(interview_stage, index) in dropdowns.interview_stages"
                                                         :key="index" :value="interview_stage.id">
                                            {{interview_stage.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Job Application" class="mb-10">
                                    <a-select allowClear v-model="filters.job_application_id">
                                        <a-select-option v-for="(job_application, index) in dropdowns.job_applications"
                                                         :key="index" :value="job_application.id">
                                            {{job_application.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Status" class="mb-10">
                                    <a-select allowClear v-model="filters.status">
                                        <a-select-option value="1">Pending</a-select-option>
                                        <a-select-option value="2">Interview Scheduled</a-select-option>
                                        <a-select-option value="3">On Hold</a-select-option>
                                        <a-select-option value="4">Rejected</a-select-option>
                                        <a-select-option value="5">Hired</a-select-option>
                                    </a-select>
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
                    :title="formTitle"
                    width="600"
                    placement="right"
                    :closable="false"
                    @close="handleFormClose"
                    :visible="formVisible"
                    :wrapStyle="{height: 'calc(100% - 108px)',overflow: 'auto', paddingBottom: '108px'}"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Status" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('status') ? 'error' : '')"
                                     :help="formErrors.first('status')">
                                    <a-select allowClear v-model="formFields.status">
                                        <a-select-option value="1">Pending</a-select-option>
                                        <a-select-option value="2">Interview Scheduled</a-select-option>
                                        <a-select-option value="3">On Hold</a-select-option>
                                        <a-select-option value="4">Rejected</a-select-option>
                                        <a-select-option value="5">Hired</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Interviewer Name" class="mb-10"
                                     :validate-status="(formErrors.has('interviewer_name') ? 'error' : '')"
                                     :help="formErrors.first('interviewer_name')">
                                    <a-input v-model="formFields.interviewer_name" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row>
                            <a-col>
                                <a-form-item label="Candidate" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('candidate_id') ? 'error' : '')"
                                     :help="formErrors.first('candidate_id')">
                                    <a-select allowClear v-model="formFields.candidate_id">
                                        <a-select-option v-for="(user, index) in dropdowns.users"
                                                         :key="index" :value="user.id">
                                            {{user.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Company" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('company_id') ? 'error' : '')"
                                     :help="formErrors.first('company_id')">
                                    <a-select allowClear v-model="formFields.company_id">
                                        <a-select-option v-for="(company, index) in dropdowns.companies"
                                                         :key="index" :disabled="company.disabled" :value="company.id">
                                            {{company.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col v-if="formFields.status === '2'">
                                <a-form-item label="Interview Stage" class="mb-10 required-input"
                                         :validate-status="(formErrors.has('interview_stage_id') ? 'error' : '')"
                                         :help="formErrors.first('interview_stage_id')">
                                    <a-select allowClear v-model="formFields.interview_stage_id">
                                        <a-select-option v-for="(interview_stage, index) in dropdowns.interview_stages"
                                                         :key="index" :value="interview_stage.id">
                                            {{interview_stage.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Interview Date" class="mb-10 required-input"
                                             :validate-status="(formErrors.has('interview_date') ? 'error' : '')"
                                             :help="formErrors.first('interview_date')">
                                    <a-date-picker v-model="formFields.interview_date" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" v-if="formFields.status === '5'">
                                <a-form-item label="Joining Date" class="mb-10"
                                             :validate-status="(formErrors.has('joining_date') ? 'error' : '')"
                                             :help="formErrors.first('joining_date')">
                                    <a-date-picker v-model="formFields.joining_date" format="DD-MM-YYYY"/>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row>
                            <a-col v-if="formFields.status === '5'">
                                <a-form-item label="Salary" class="mb-10"
                                             :validate-status="(formErrors.has('salary') ? 'error' : '')"
                                             :help="formErrors.first('salary')">
                                    <a-input v-model="formFields.salary" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Note" class="mb-10"
                                             :validate-status="(formErrors.has('notes') ? 'error' : '')"
                                             :help="formErrors.first('notes')">
                                    <a-textarea v-model="formFields.notes" />
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
                    class="fit-table interviews-table"
                    :columns="columns"
                    :rowKey="record => record.id"
                    :dataSource="dataSource"
                    :pagination="pagination"
                    :loading="loading"
                    @change="handleTableChange">
                    <template slot="candidate_title" slot-scope="text, record, index" class="text-right">
                        {{record.candidate}}
                    </template>
                    <template slot="company_title" slot-scope="text, record, index" class="text-right">
                        {{record.company}}
                    </template>
                    <template slot="interview_stage_title" slot-scope="text, record, index" class="text-right">
                        {{record.interview_stage}}
                    </template>
                    <template slot="job_application_number" slot-scope="text, record, index" class="text-right">
                        {{record.job_application_number}}
                    </template>
                    <template slot="status" slot-scope="text, record, index" class="text-right">
                        {{record._status_}}
                    </template>
                    <template slot="action" slot-scope="text, record, index" class="text-right">
                        <a-button
                            size="small"
                            type="default"
                            shape="circle" class="mr-5"
                            title="Edit" @click="handleEditRecord(record.id)"
                            v-if="!record.hired_at && !record.rejected_at">
                            <a-icon type="edit" />
                        </a-button>
                        <a-popconfirm
                            placement="left"
                            title="Sure to delete?"
                            v-if="!record.hired_at && !record.rejected_at"
                            @confirm="() => handleDeleteRecord(record.id)"
                        >
                            <a-button size="small" type="default" shape="circle" title="Delete">
                                <a-icon type="delete" />
                            </a-button>
                        </a-popconfirm>
                    </template>
                    <div slot="expandedRowRender" slot-scope="record">
                        <p><strong>Interviewer Name:</strong> {{record.interviewer_name}}</p>
                        <p><strong>Interview Date:</strong> {{(record.interview_date) ? momentFormatter(record.interview_date).format('DD-MM-YYYY') : null}}</p>
                        <p><strong>Joining Date:</strong> {{(record.joining_date) ? momentFormatter(record.joining_date).format('DD-MM-YYYY') : null}}</p>
                        <p><strong>Salary:</strong> {{record.salary}}</p>
                        <p><strong>Notes:</strong> {{record.notes}}</p>
                        <div v-if="record.interview_stage_history.length">
                            <p><strong>Stage History:</strong></p>
                            <a-timeline>
                                <a-timeline-item v-for="(ish, index) in record.interview_stage_history" :key="index">
                                    {{momentFormatter(ish.created_at).format('DD-MM-YYYY')}} {{ish.interview_stage}}
                                </a-timeline-item>
                            </a-timeline>
                        </div>
                    </div>
                </a-table>
            </div>
        </a-col>
    </a-row>
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
                },
                loading: false,
                columns: [
                    {
                        title: 'Candidate',
                        dataIndex: 'candidate_id',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'candidate_title' },
                    },
                    {
                        title: 'Company',
                        dataIndex: 'company_id',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'company_title' },
                    },
                    {
                        title: 'Current Stage',
                        dataIndex: 'interview_stage_id',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'interview_stage_title' },
                    },
                    {
                        title: 'Job App. No.',
                        dataIndex: 'job_application_id',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'job_application_number' },
                    },
                    {
                        title: 'Status',
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
                }
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
                    url: `interviews`,
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
                        interview_date: (response.data.interview_date) ? moment(response.data.interview_date) : null,
                        joining_date: (response.data.joining_date) ? moment(response.data.joining_date) : null,
                    };
                    this.handleFormClick();
                })
                .catch((errors) => {

                })
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
