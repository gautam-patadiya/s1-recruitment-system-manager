<template>
    <b-row>
        <b-col cols="12">
            <b-row class="align-items-center job-header-simple">
                <b-col cols="12" md="6">
                    <h2 class="job-header-title">
                        Companies
                    </h2>
                </b-col>

                <b-col cols="12" md="6" class="text-md-right mt-15 mt-md-0">
                    <b-button variant="primary" class="mr-10" @click="handleFormClick" title="Add New Company">
                        <i class="bi bi-plus-circle mr-5"></i>
                        New Company
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
                        <b-form-group label="Name" class="mb-10">
                            <b-form-input v-model="filters.name"></b-form-input>
                        </b-form-group>
                        <b-form-group label="Email" class="mb-10">
                            <b-form-input v-model="filters.email"></b-form-input>
                        </b-form-group>
                        <b-form-group label="Phone" class="mb-10">
                            <b-form-input v-model="filters.phone"></b-form-input>
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
                    sidebar-class="company-form-sidebar"
                    body-class="company-form-sidebar-body"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Name *"
                                    class="mb-10 required-input"
                                    :state="invalidState('name')"
                                    :invalid-feedback="formErrors.first('name')"
                                >
                                    <b-form-input v-model="formFields.name"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Email *"
                                    class="mb-10 required-input"
                                    :state="invalidState('email')"
                                    :invalid-feedback="formErrors.first('email')"
                                >
                                    <b-form-input v-model="formFields.email"></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Phone *"
                                    class="mb-10 required-input"
                                    :state="invalidState('phone')"
                                    :invalid-feedback="formErrors.first('phone')"
                                >
                                    <b-form-input v-model="formFields.phone"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Is active? *" class="mb-10 required-input">
                                    <b-form-checkbox v-model="formFields.status">Yes</b-form-checkbox>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Additional Contact"
                                    class="mb-10"
                                    :state="invalidState('additional_contact')"
                                    :invalid-feedback="formErrors.first('additional_contact')"
                                >
                                    <b-form-input v-model="formFields.additional_contact"></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Additional Contact Number"
                                    class="mb-10"
                                    :state="invalidState('additional_contact_number')"
                                    :invalid-feedback="formErrors.first('additional_contact_number')"
                                >
                                    <b-form-input v-model="formFields.additional_contact_number"></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-form-group
                            label="Description *"
                            class="mb-10 required-input"
                            :state="invalidState('description')"
                            :invalid-feedback="formErrors.first('description')"
                        >
                            <b-form-textarea v-model="formFields.description"></b-form-textarea>
                        </b-form-group>
                        <b-form-group
                            label="Address *"
                            class="mb-10 required-input"
                            :state="invalidState('address')"
                            :invalid-feedback="formErrors.first('address')"
                        >
                            <b-form-textarea v-model="formFields.address"></b-form-textarea>
                        </b-form-group>
                        <div class="filter-footer text-right">
                            <b-button variant="primary" type="submit" class="mr-5">Submit</b-button>
                            <b-button @click="handleFormClose">Cancel</b-button>
                        </div>
                    </form>
                </b-sidebar>

                <b-table
                    class="fit-table companies-table company-modern-table"
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
                    <template #cell(name)="data">
                        <div class="job-title-cell">
                            <div class="job-title-text">{{data.item.name}}</div>
                            <div class="job-meta-line">{{data.item.email}}</div>
                        </div>
                    </template>
                    <template #cell(additional_contact)="data">
                        <div class="company-contact-cell">
                            <div>{{data.item.additional_contact || 'Not Set'}}</div>
                            <div class="job-meta-line">{{data.item.additional_contact_number || 'No number'}}</div>
                        </div>
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
                                    <p><strong>Description:</strong> {{row.item.description}}</p>
                                </b-col>
                                <b-col cols="12" md="6">
                                    <p><strong>Address:</strong> {{row.item.address}}</p>
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
        companyCreated, companyDeleted, companyUpdated,
        deleteDependent,
        qualificationCreated, qualificationDeleted, qualificationUpdated,
        serverError,
    } from "../../util/notify";

    const DEFAULT_FORM_STATE = {
        id: null,
        status: false,
        name: null,
        phone: null,
        additional_contact: null,
        additional_contact_number: null,
        description: null,
        address: null,
    };

    export default {
        mixins: [mixins],
        data() {
            return {
                formTitle: 'Add Company',
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
                        key: 'name',
                        label: 'Company',
                        sortable: true,
                    },
                    {
                        key: 'phone',
                        label: 'Phone',
                        sortable: true,
                    },
                    {
                        key: 'additional_contact',
                        label: 'Add. Contact',
                        sortable: true,
                    },
                    {
                        key: 'status',
                        label: 'Is Active?',
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
                    name: null,
                    email: null,
                    phone: null,
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
                    url: `companies`,
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
                this.formTitle = 'Add Company';
                this.formErrors = new Error({});
                this.formFields = {...DEFAULT_FORM_STATE};
            },
            handleFormSubmit() {
                request({
                    method: "post",
                    url: this.formFields.id ? `companies/update` : `companies/create`,
                    data: {...this.formFields},
                })
                .then((response) => {
                    this.loadList(this.listQueryParams);

                    if (this.formFields.id) {
                        companyUpdated();
                    } else {
                        companyCreated();
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
                    url: `/companies/detail/${id}`,
                })
                .then((response) => {
                    this.formTitle = 'Edit Company';
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
                    url: `/companies/delete`,
                    data: {
                        id: id
                    }
                })
                .then((response) => {
                    companyDeleted();
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
                this.csv.config.filename = 'companies';
                this.csv.config.headers = ['Name', 'Email', 'Phone', 'Additional Contact', 'Additional Contact Number', 'Description', 'Address'];
                this.csv.dataSource = this.dataSource.map((item) => this.exportFields(item));
                this.exportCsv();
            },
            handlePdfExportClick() {
                this.pdf.title = 'All Companies';
                this.pdf.filename = 'companies';
                this.pdf.body = this.dataSource.map((item, index) => {
                    return {
                        ...this.exportFields(item),
                        index: (index + 1),
                    }
                });
                this.pdf.columns = [
                    {header: '#', dataKey: 'index'},
                    {header: 'Name', dataKey: 'name'},
                    {header: 'Email', dataKey: 'email'},
                    {header: 'Phone', dataKey: 'phone'},
                    {header: 'Add. Contact', dataKey: 'additional_contact'},
                    {header: 'Add. Contact No.', dataKey: 'additional_contact_number'},
                    {header: 'Description', dataKey: 'description'},
                    {header: 'Address', dataKey: 'address'},
                ];
                this.exportPdf();
            },
            exportFields(item) {
                return {
                    name: item.name,
                    email: item.email,
                    phone: item.phone,
                    additional_contact: item.additional_contact,
                    additional_contact_number: item.additional_contact_number,
                    description: item.description,
                    address: item.address,
                }
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
        .fit-table.companies-table td:before{ font-weight: bold; }
        .fit-table.companies-table td:nth-of-type(1):before { content: "#"; }
        .fit-table.companies-table td:nth-of-type(2):before { content: "Name"; }
        .fit-table.companies-table td:nth-of-type(3):before { content: "Email"; }
        .fit-table.companies-table td:nth-of-type(4):before { content: "Phone"; }
        .fit-table.companies-table td:nth-of-type(5):before { content: "Add. Contact"; }
        .fit-table.companies-table td:nth-of-type(6):before { content: "Add. Contact No."; }
        .fit-table.companies-table td:nth-of-type(7):before { content: "Is Active?"; }
        .fit-table.companies-table td:nth-of-type(8):before { content: "Action"; }
    }
</style>
