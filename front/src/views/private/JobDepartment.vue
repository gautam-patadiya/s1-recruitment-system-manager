<template>
    <a-row>
        <a-col span="24">
            <div>
                <a-row>
                    <a-col :xs="24" :sm="24" :md="12">
                        <h2 class="mb-0">
                            All Job<strong> Departments</strong>
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
                                <a-form-item label="Name" class="mb-10">
                                    <a-input v-model="filters.name" />
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
                    placement="right"
                    :closable="false"
                    @close="handleFormClose"
                    :visible="formVisible"
                    :wrapStyle="{height: 'calc(100% - 108px)',overflow: 'auto', paddingBottom: '108px'}"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <a-row>
                            <a-col>
                                <a-form-item label="Name" class="mb-10 required-input"
                                 :validate-status="(formErrors.has('name') ? 'error' : '')"
                                 :help="formErrors.first('name')">
                                    <a-input v-model="formFields.name" />
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
                    :columns="columns"
                    :rowKey="record => record.id"
                    :dataSource="dataSource"
                    :pagination="pagination"
                    :loading="loading"
                    @change="handleTableChange">
                    <template slot="action" slot-scope="text, record, index" class="text-right">
                        <a-button size="small" type="default" shape="circle" class="mr-5" title="Edit" @click="handleEditRecord(record.id)">
                            <a-icon type="edit" />
                        </a-button>
                        <a-popconfirm
                            placement="left"
                            title="Sure to delete?"
                            @confirm="() => handleDeleteRecord(record.id)"
                        >
                            <a-button size="small" type="default" shape="circle" title="Delete">
                                <a-icon type="delete" />
                            </a-button>
                        </a-popconfirm>
                    </template>
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
        deleteDependent,
        jobDepartmentCreated,
        jobDepartmentDeleted,
        jobDepartmentUpdated,
        serverError,
    } from "../../util/notify";

    const DEFAULT_FORM_STATE = {
        id: null,
        name: null,
    };

    export default {
        mixins: [mixins],
        data() {
            return {
                formTitle: 'Add Job Department',
                dataSource: [],
                pagination: {
                    page: 1,
                },
                loading: false,
                columns: [
                    {
                        title: 'Name',
                        dataIndex: 'name',
                        width: 150,
                        sorter: true,
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
                },
                formVisible: false,
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({})
            }
        },
        mounted() {
            this.loadList({ page: 1 });
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
                    ...filters
                };
                this.listQueryParams = listQueryParams;
                this.loadList(listQueryParams);
            },
            loadList(listQueryParams) {
                this.loading = true;
                this.formErrors = new Error({});
                request({
                    method: "get",
                    url: `job/departments`,
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
                this.formTitle = 'Add Job Department';
                this.formErrors = new Error({});
                this.formFields = {...DEFAULT_FORM_STATE};
            },
            handleFormSubmit() {
                request({
                    method: "post",
                    url: this.formFields.id ? `job/departments/update` : `job/departments/create`,
                    data: {...this.formFields},
                })
                .then((response) => {
                    this.loadList(this.listQueryParams);

                    if (this.formFields.id) {
                        jobDepartmentUpdated();
                    } else {
                        jobDepartmentCreated();
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
                    url: `/job/departments/detail/${id}`,
                })
                .then((response) => {
                    this.formTitle = 'Edit Job Department';
                    this.formFields = {...response.data};
                    this.handleFormClick();
                })
                .catch((errors) => {

                })
            },
            handleDeleteRecord(id) {
                request({
                    method: "post",
                    url: `/job/departments/delete`,
                    data: {
                        id: id
                    }
                })
                .then((response) => {
                    jobDepartmentDeleted();
                    this.loadList(this.listQueryParams);
                })
                .catch((errors) => {
                    if(errors.status && errors.status === 419) {
                        deleteDependent();
                        return;
                    }

                    serverError();
                })
            },
            handleCsvExportClick() {
                this.csv.config.filename = 'job_departments';
                this.csv.config.headers = ['Name'];
                this.csv.dataSource = this.dataSource.map((item) => { return { name: item.name } });
                this.exportCsv();
            },
            handlePdfExportClick() {
                this.pdf.title = 'All Job Department';
                this.pdf.filename = 'job_departments';
                this.pdf.body = this.dataSource.map((item, index) => {
                    return {name: item.name, index: (index + 1)}
                });
                this.pdf.columns = [
                    {header: '#', dataKey: 'index'},
                    {header: 'Name', dataKey: 'name'},
                ];
                this.exportPdf();
            }
        },
        computed: {

        }
    }
</script>
