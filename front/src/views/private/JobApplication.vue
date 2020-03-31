<template>
    <a-row>
        <a-col span="24">
            <div>
                <a-row>
                    <a-col :xs="24" :sm="24" :md="12">
                        <h2 class="mb-0">
                            All Job <strong>Applications</strong>
                        </h2>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" class="text-right">
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
                            <a-col>
                                <a-form-item label="Job Application Number" class="mb-10">
                                    <a-input v-model="filters.job_application_number" />
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
                        <a-row>
                            <a-col>
                                <a-form-item label="Status" class="mb-10 required-input"
                                         :validate-status="(formErrors.has('status') ? 'error' : '')"
                                         :help="formErrors.first('status')">
                                    <a-select allowClear v-model="formFields.status">
                                        <a-select-option value="1">Pending</a-select-option>
                                        <a-select-option value="2">Interview Scheduled</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col v-if="formFields.status === '2'">
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
                            <a-col v-if="formFields.status === '2'">
                                <a-form-item label="Interview Date" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('interview_date') ? 'error' : '')"
                                     :help="formErrors.first('interview_date')">
                                    <a-date-picker v-model="formFields.interview_date" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                            <a-col v-if="formFields.status === '2'">
                                Action will hide the Edit button and create Interview
                            </a-col>
                        </a-row>
                        <div class="filter-footer text-right">
                            <a-button type="primary" html-type="submit" class="mr-5">Submit</a-button>
                            <a-button @click="handleFormClose">Cancel</a-button>
                        </div>
                    </form>
                </a-drawer>

                <a-table
                    class="fit-table job-applications-table"
                    :columns="columns"
                    :rowKey="record => record.id"
                    :dataSource="dataSource"
                    :pagination="pagination"
                    :loading="loading"
                    @change="handleTableChange">
                    <template slot="action" slot-scope="text, record, index" class="text-right">
                        <a-button
                            size="small"
                            type="default" shape="circle" class="mr-5" title="Edit"
                            @click="handleEditRecord(record.id)"
                            v-if="record.status <= 1">
                            <a-icon type="edit" />
                        </a-button>
                        <a-popconfirm
                            v-if="record.status > 1"
                            placement="left"
                            title="Sure to make it pending?"
                            @confirm="() => handlePendingAgain(record.id)"
                        >
                            <a-button size="small" type="default" shape="circle" title="Make it Pending">
                                <a-icon type="caret-left" />
                            </a-button>
                        </a-popconfirm>
                    </template>
                    <template slot="job_title" slot-scope="text, record, index">
                        {{record.job.title}}
                    </template>
                    <template slot="job_company" slot-scope="text, record, index">
                        {{record.job.company}}
                    </template>
                    <template slot="candidate_name" slot-scope="text, record, index">
                        {{record.candidate.first_name}} {{record.candidate.last_name}}
                    </template>\
                    <template slot="status" slot-scope="text, record, index" class="text-center">
                        {{record._status_}}
                    </template>
                    <template slot="applied_at" slot-scope="text, record, index" class="text-center">
                        {{covertData(record.created_at)}}
                    </template>
                    <div slot="expandedRowRender" slot-scope="record">
                        <p><strong>Company:</strong> {{record.job.company}}</p>
                        <p><strong>Department:</strong> {{record.job.department}}</p>
                        <p v-if="record.user_document">
                            <strong>Uploaded Document:</strong>
                            <a target="_blank" :href="record.user_document.download_url">
                                {{record.user_document.document_type}} - {{record.user_document.filename}}
                            </a>
                        </p>
                    </div>
                </a-table>
            </div>
        </a-col>
    </a-row>
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
                },
                loading: false,
                columns: [
                    {
                        title: 'Number',
                        dataIndex: 'job_application_number',
                        width: 75,
                        sorter: true,
                    },
                    {
                        title: 'Candidate',
                        dataIndex: 'candidate_id',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'candidate_name' },
                    },
                    {
                        title: 'Job Title',
                        dataIndex: 'job_id',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'job_title' },
                    },
                    {
                        title: 'Job Company',
                        dataIndex: 'company',
                        width: 150,
                        sorter: false,
                        scopedSlots: { customRender: 'job_company' },
                    },
                    {
                        title: 'Status',
                        dataIndex: 'status',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'status' },
                    },
                    {
                        title: 'Applied At',
                        dataIndex: 'created_at',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'applied_at' },
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
                }
            }
        },
        mounted() {
            this.loadList({ page: 1 });
            this.getInterviewStages();
            this.getCompanies();
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
                    url: `job/applications`,
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
                            interview_date: (response.data.interview_date) ? moment(response.data.interview_date) : null,
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

        }
    }
</script>
<style lang="scss">
    .ant-drawer-body {
        margin-bottom: 30px;
    }

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
