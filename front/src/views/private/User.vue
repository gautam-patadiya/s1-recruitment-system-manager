<template>
    <b-row>
        <b-col cols="12">
            <b-row class="align-items-center job-header-simple">
                <b-col cols="12" md="6">
                    <h2 class="job-header-title">
                        Users
                    </h2>
                </b-col>

                <b-col cols="12" md="6" class="text-md-right mt-15 mt-md-0">
                    <b-button variant="primary" class="mr-10" @click="handleFormClick" title="Add New User">
                        <i class="bi bi-plus-circle mr-5"></i>
                        New User
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
                    shadow
                    backdrop
                    no-header-close
                >
                    <form @submit.prevent="handleSearch" autocomplete="off" class="p-3">
                        <b-form-group label="First Name" class="mb-10">
                            <b-form-input v-model="filters.first_name"></b-form-input>
                        </b-form-group>
                        <b-form-group label="Last Name" class="mb-10">
                            <b-form-input v-model="filters.last_name"></b-form-input>
                        </b-form-group>
                        <b-form-group label="Email" class="mb-10">
                            <b-form-input v-model="filters.email"></b-form-input>
                        </b-form-group>
                        <b-form-group label="Gender" class="mb-10">
                            <v-select
                                v-model="filters.gender"
                                :options="genderOptions"
                                label="text"
                                :reduce="option => option.value"
                                placeholder="Select Gender"
                            ></v-select>
                        </b-form-group>
                        <b-form-group label="User Type" class="mb-10">
                            <v-select
                                v-model="filters.type"
                                :options="typeOptions"
                                label="text"
                                :reduce="option => option.value"
                                placeholder="Select User Type"
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
                    sidebar-class="user-form-sidebar"
                    body-class="user-form-sidebar-body"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <b-form-group
                            label="First Name *"
                            class="mb-10 required-input"
                            :state="invalidState('first_name')"
                            :invalid-feedback="formErrors.first('first_name')"
                        >
                            <b-form-input v-model="formFields.first_name"></b-form-input>
                        </b-form-group>
                        <b-form-group
                            label="Last Name *"
                            class="mb-10 required-input"
                            :state="invalidState('last_name')"
                            :invalid-feedback="formErrors.first('last_name')"
                        >
                            <b-form-input v-model="formFields.last_name"></b-form-input>
                        </b-form-group>
                        <b-form-group
                            :label="formFields.id ? 'Password' : 'Password *'"
                            class="mb-10"
                            :class="{'required-input': !formFields.id}"
                            :state="invalidState('password')"
                            :invalid-feedback="formErrors.first('password')"
                        >
                            <b-form-input type="password" v-model="formFields.password"></b-form-input>
                        </b-form-group>
                        <b-form-group
                            :label="formFields.id ? 'Password Confirm' : 'Password Confirm *'"
                            class="mb-10"
                            :class="{'required-input': !formFields.id}"
                            :state="invalidState('password_confirmation')"
                            :invalid-feedback="formErrors.first('password_confirmation')"
                        >
                            <b-form-input type="password" v-model="formFields.password_confirmation"></b-form-input>
                        </b-form-group>
                        <b-form-group
                            label="Email *"
                            class="mb-10 required-input"
                            :state="invalidState('email')"
                            :invalid-feedback="formErrors.first('email')"
                        >
                            <b-form-input v-model="formFields.email"></b-form-input>
                        </b-form-group>
                        <b-form-group
                            label="Gender *"
                            class="mb-10 required-input"
                            :state="invalidState('gender')"
                            :invalid-feedback="formErrors.first('gender')"
                        >
                            <v-select
                                v-model="formFields.gender"
                                :options="genderOptions"
                                label="text"
                                :reduce="option => option.value"
                                placeholder="Select Gender"
                            ></v-select>
                        </b-form-group>
                        <b-form-group
                            label="Birthday *"
                            class="mb-10 required-input"
                            :state="invalidState('birthday')"
                            :invalid-feedback="formErrors.first('birthday')"
                        >
                            <date-picker
                                v-model="formFields.birthday"
                                format="DD-MM-YYYY"
                                value-type="YYYY-MM-DD"
                                placeholder=""
                            />
                        </b-form-group>
                        <b-form-group
                            :label="formFields.id ? 'User Type' : 'User Type *'"
                            class="mb-10"
                            :class="{'required-input': !formFields.id}"
                            :state="invalidState('type')"
                            :invalid-feedback="formErrors.first('type')"
                        >
                            <v-select
                                v-model="formFields.type"
                                :options="typeOptions"
                                label="text"
                                :reduce="option => option.value"
                                placeholder="Select User Type"
                            ></v-select>
                        </b-form-group>
                        <b-form-group label="is active?" class="mb-10">
                            <b-form-checkbox v-model="formFields.status">Yes</b-form-checkbox>
                        </b-form-group>
                        <div class="filter-footer text-right">
                            <b-button variant="primary" type="submit" class="mr-5">Submit</b-button>
                            <b-button @click="handleFormClose">Cancel</b-button>
                        </div>
                    </form>
                </b-sidebar>

                <b-table
                    class="fit-table users-table user-modern-table"
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
                    <template #cell(first_name)="data">
                        <div class="job-title-cell">
                            <div class="job-title-text">
                                {{data.item.first_name}} {{data.item.last_name}}
                            </div>
                            <div class="job-meta-line">
                                {{data.item.email}}
                            </div>
                        </div>
                    </template>
                    <template #cell(gender)="data">
                        <b-badge class="user-light-badge">
                            <span v-if="data.value === 1">Male</span>
                            <span v-else>Female</span>
                        </b-badge>
                    </template>
                    <template #cell(birthday)="data">
                        <span>{{momentFormatter(data.value).format('DD-MM-YYYY')}}</span>
                    </template>
                    <template #cell(type)="data">
                        <b-badge class="user-type-badge">
                            <span v-if="data.value === 1">Admin</span>
                            <span v-if="data.value === 2">Candidate</span>
                        </b-badge>
                    </template>
                    <template #cell(email_verified_at)="data">
                        <b-badge v-if="data.item.is_active" class="job-status-badge job-status-active">Active</b-badge>
                        <b-badge v-if="!data.item.is_active" class="job-status-badge job-status-inactive">Inactive</b-badge>
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
    import {handleServerError} from "../../util/utils";
    import {serverError, userCreated, userDeleted, userUpdated} from "../../util/notify";

    const DEFAULT_FORM_STATE = {
        id: null,
        first_name: null,
        last_name: null,
        password: null,
        password_confirmation: null,
        email: null,
        gender: null,
        birthday: null,
        status: false,
        type: '2',
    };

    export default {
        mixins: [mixins],
        data() {
            return {
                formTitle: 'Add User',
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
                        key: 'first_name',
                        label: 'User',
                        sortable: true,
                    },
                    {
                        key: 'gender',
                        label: 'Gender',
                        sortable: true,
                    },
                    {
                        key: 'birthday',
                        label: 'Birthday',
                        sortable: true,
                    },
                    {
                        key: 'type',
                        label: 'Type',
                        sortable: true,
                    },
                    {
                        key: 'email_verified_at',
                        label: 'Is Active?',
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
                    first_name: null,
                    last_name: null,
                    email: null,
                    gender: null,
                    type: null,
                },
                formVisible: false,
                formFields: {...DEFAULT_FORM_STATE},
                formErrors: new Error({}),
                genderOptions: [
                    {value: '1', text: 'Male'},
                    {value: '2', text: 'Female'},
                ],
                typeOptions: [
                    {value: '1', text: 'Admin'},
                    {value: '2', text: 'Candidate'},
                ],
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
                    ...filters
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
                    url: `users`,
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
                this.formTitle = 'Add User';
                this.formErrors = new Error({});
                this.formFields = {...DEFAULT_FORM_STATE};
            },
            handleFormSubmit() {
                request({
                    method: "post",
                    url: this.formFields.id ? `users/update` : `users/create`,
                    data: {...this.formFields, birthday: ((this.formFields.birthday) ? moment(this.formFields.birthday).format('YYYY-MM-DD') : null)},
                })
                .then((response) => {
                    this.loadList(this.listQueryParams);

                    if (this.formFields.id) {
                        userUpdated();
                    } else {
                        userCreated();
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
                    url: `/users/detail/${id}`,
                })
                .then((response) => {
                    this.formTitle = 'Edit User';
                    this.formFields = {
                        ...response.data,
                        password: null,
                        birthday: response.data.birthday ? moment(response.data.birthday).format('YYYY-MM-DD') : null,
                        gender: response.data.gender.toString(),
                        status: response.data.is_active,
                        type: response.data.type.toString()
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
                    url: `/users/delete`,
                    data: {
                        id: id
                    }
                })
                .then((response) => {
                    userDeleted();
                    this.loadList(this.listQueryParams);
                })
                .catch((errors) => {
                    serverError();
                })
            },
            handleCsvExportClick() {
                this.csv.config.filename = 'users';
                this.csv.config.headers = ['First Name', 'Last Name', 'Email', 'Gender', 'Birthday', 'Type'];
                this.csv.dataSource = this.dataSource.map((item) => { return {
                    first_name: item.first_name,
                    last_name: item.last_name,
                    email: item.email,
                    gender: item.gender === 1 ? "Male" : "Female",
                    birthday: this.momentFormatter(item.birthday).format('DD-MM-YYYY'),
                    type: item.type === 1 ? "Admin" : "Candidate",
                } });
                this.exportCsv();
            },
            handlePdfExportClick() {
                this.pdf.title = 'All Users';
                this.pdf.filename = 'users';
                this.pdf.body = this.dataSource.map((item, index) => {
                    return {
                        index: (index + 1),
                        first_name: item.first_name,
                        last_name: item.last_name,
                        email: item.email,
                        gender: item.gender === 1 ? "Male" : "Female",
                        birthday: this.momentFormatter(item.birthday).format('DD-MM-YYYY'),
                        type: item.type === 1 ? "Admin" : "Candidate",
                    }
                });
                this.pdf.columns = [
                    {header: '#', dataKey: 'index'},
                    {header: 'First Name', dataKey: 'first_name'},
                    {header: 'Last Name', dataKey: 'last_name'},
                    {header: 'Email', dataKey: 'email'},
                    {header: 'Gender', dataKey: 'gender'},
                    {header: 'Birthday', dataKey: 'birthday'},
                    {header: 'Type', dataKey: 'type'},
                ];
                this.exportPdf();
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
        .fit-table.users-table td:before{ font-weight: bold; }
        .fit-table.users-table td:nth-of-type(1):before { content: "#"; }
        .fit-table.users-table td:nth-of-type(2):before { content: "First Name"; }
        .fit-table.users-table td:nth-of-type(3):before { content: "Last Name"; }
        .fit-table.users-table td:nth-of-type(4):before { content: "Gender"; }
        .fit-table.users-table td:nth-of-type(5):before { content: "Birthday"; }
        .fit-table.users-table td:nth-of-type(6):before { content: "Type"; }
        .fit-table.users-table td:nth-of-type(7):before { content: "Is Active?"; }
        .fit-table.users-table td:nth-of-type(8):before { content: "Action"; }
    }
</style>
