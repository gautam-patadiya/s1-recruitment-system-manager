<template>
    <b-row>
        <b-col cols="12">
            <b-row class="align-items-center job-header-simple">
                <b-col cols="12" md="6">
                    <h2 class="job-header-title">
                        Job Departments
                    </h2>
                </b-col>

                <b-col cols="12" md="6" class="text-md-right mt-15 mt-md-0">
                    <b-button variant="primary" class="mr-10" @click="handleFormClick" title="Add New Job Department">
                        <i class="bi bi-plus-circle mr-5"></i>
                        New Department
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
                <b-sidebar
                    v-model="filterVisible"
                    title="Filter Panel"
                    backdrop
                    shadow
                    no-header-close
                    @hidden="handleFilterClose"
                >
                    <form @submit.prevent="handleSearch" autocomplete="off" class="p-3">
                        <b-form-group label="Name" class="mb-10">
                            <b-form-input v-model="filters.name"></b-form-input>
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
                    backdrop
                    shadow
                    no-header-close
                    width="760px"
                    sidebar-class="master-form-sidebar"
                    body-class="master-form-sidebar-body"
                    @hidden="handleFormClose"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <b-form-group
                            label="Name *"
                            class="mb-10 required-input"
                            :state="formErrors.has('name') ? false : null"
                            :invalid-feedback="formErrors.first('name')"
                        >
                            <b-form-input
                                v-model="formFields.name"
                                :state="formErrors.has('name') ? false : null"
                            ></b-form-input>
                        </b-form-group>
                        <div class="filter-footer text-right">
                            <b-button variant="primary" type="submit" class="mr-5">Submit</b-button>
                            <b-button @click="handleFormClose">Cancel</b-button>
                        </div>
                    </form>
                </b-sidebar>

                <b-table
                    class="fit-table job-departments-table master-modern-table"
                    responsive
                    hover
                    show-empty
                    empty-text="No data available"
                    :items="dataSource"
                    :fields="fields"
                    :busy="loading"
                    no-local-sorting
                    @sort-changed="handleTableChange"
                >
                    <template #cell(name)="data">
                        <span class="job-title-text">{{data.item.name}}</span>
                    </template>
                    <template #cell(action)="data">
                        <div class="job-action-buttons text-right">
                            <b-button size="sm" variant="outline-primary" class="mr-5" title="Edit" @click="handleEditRecord(data.item.id)">
                                <i class="bi bi-pencil-square mr-5"></i>
                                Edit
                            </b-button>
                            <b-button size="sm" variant="outline-danger" title="Delete" @click="confirmDeleteRecord(data.item.id)">
                                <i class="bi bi-trash mr-5"></i>
                                Delete
                            </b-button>
                        </div>
                    </template>
                </b-table>

                <b-pagination
                    v-if="pagination && pagination.total"
                    v-model="currentPage"
                    :total-rows="pagination.total"
                    :per-page="getPageSize()"
                    align="right"
                    class="mt-3"
                    @change="handlePageChange"
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
                    total: 0,
                    per_page: 10,
                },
                currentPage: 1,
                loading: false,
                fields: [
                    {
                        key: 'name',
                        label: 'Name',
                        sortable: true,
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
                    name: null,
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
            getPageSize() {
                return this.pagination.per_page || this.pagination.pageSize || 10;
            },
            handleTableChange(sortContext) {
                const pager = { ...this.pagination };
                pager.current = this.currentPage;
                this.pagination = pager;
                const listQueryParams = {
                    ...this.listQueryParams,
                    page: this.currentPage,
                    pageSize: this.getPageSize(),
                    sortField: sortContext.sortBy,
                    sortOrder: (sortContext.sortDesc ? 'desc' : 'asc'),
                };
                this.listQueryParams = listQueryParams;
                this.loadList(listQueryParams);
            },
            handlePageChange(page) {
                const listQueryParams = {
                    ...this.listQueryParams,
                    page: page,
                    pageSize: this.getPageSize(),
                };
                this.currentPage = page;
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
                    url: `job/departments`,
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
                    this.currentPage = this.pagination.current_page || this.pagination.current || this.pagination.page || this.currentPage;
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
                this.currentPage = 1;
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
            confirmDeleteRecord(id) {
                if (window.confirm('Sure to delete?')) {
                    this.handleDeleteRecord(id);
                }
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
