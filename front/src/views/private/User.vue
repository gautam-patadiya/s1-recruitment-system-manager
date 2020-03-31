<template>
    <a-row>
        <a-col span="24">
            <div>
                <a-row>
                    <a-col :xs="24" :sm="24" :md="12">
                        <h2 class="mb-0">
                            All <strong>Users</strong>
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
                                <a-form-item label="First Name" class="mb-10">
                                    <a-input v-model="filters.first_name" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Last Name" class="mb-10">
                                    <a-input v-model="filters.last_name" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Email" class="mb-10">
                                    <a-input v-model="filters.email" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Gender" class="mb-10">
                                    <a-select allowClear v-model="filters.gender">
                                        <a-select-option value="1">Male</a-select-option>
                                        <a-select-option value="2">Female</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="User Type" class="mb-10">
                                    <a-select allowClear v-model="filters.type">
                                        <a-select-option value="1">Admin</a-select-option>
                                        <a-select-option value="2">Candidate</a-select-option>
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
                    placement="right"
                    :closable="false"
                    @close="handleFormClose"
                    :visible="formVisible"
                    :wrapStyle="{height: 'calc(100% - 108px)',overflow: 'auto', paddingBottom: '108px'}"
                >
                    <form @submit.prevent="handleFormSubmit" autocomplete="off">
                        <a-row>
                            <a-col>
                                <a-form-item label="First Name" class="mb-10 required-input"
                                 :validate-status="(formErrors.has('first_name') ? 'error' : '')"
                                 :help="formErrors.first('first_name')">
                                    <a-input v-model="formFields.first_name" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Last Name" class="mb-10 required-input"
                                 :validate-status="(formErrors.has('last_name') ? 'error' : '')"
                                 :help="formErrors.first('last_name')">
                                    <a-input v-model="formFields.last_name" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Password" class="mb-10"
                                 :class="{'required-input': !formFields.id}"
                                 :validate-status="(formErrors.has('password') ? 'error' : '')"
                                 :help="formErrors.first('password')">
                                    <a-input type="password" v-model="formFields.password" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Password Confirm" class="mb-10"
                                 :class="{'required-input': !formFields.id}"
                                 :validate-status="(formErrors.has('password_confirmation') ? 'error' : '')"
                                 :help="formErrors.first('password_confirmation')">
                                    <a-input type="password" v-model="formFields.password_confirmation" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Email" class="mb-10 required-input"
                                 :validate-status="(formErrors.has('email') ? 'error' : '')"
                                 :help="formErrors.first('email')">
                                    <a-input v-model="formFields.email" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Gender" class="mb-10 required-input"
                                 :validate-status="(formErrors.has('gender') ? 'error' : '')"
                                 :help="formErrors.first('gender')">
                                    <a-select allowClear v-model="formFields.gender">
                                        <a-select-option value="1">Male</a-select-option>
                                        <a-select-option value="2">Female</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Birthday" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('birthday') ? 'error' : '')"
                                     :help="formErrors.first('birthday')">
                                    <a-date-picker v-model="formFields.birthday" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="User Type" class="mb-10 required-input"
                                             :validate-status="(formErrors.has('type') ? 'error' : '')"
                                             :help="formErrors.first('type')">
                                    <a-select allowClear v-model="formFields.type">
                                        <a-select-option value="1">Admin</a-select-option>
                                        <a-select-option value="2">Candidate</a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="is active?"  class="mb-10">
                                    <a-checkbox :checked="formFields.status" v-model="formFields.status">
                                        Yes
                                    </a-checkbox>
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
                    class="fit-table users-table"
                    :columns="columns"
                    :rowKey="record => record.id"
                    :dataSource="dataSource"
                    :pagination="pagination"
                    :loading="loading"
                    @change="handleTableChange">
                    <span slot="gender" slot-scope="gender">
                        <span v-if="gender === 1">Male</span>
                        <span v-else>FeMale</span>
                    </span>
                    <span slot="birthday" slot-scope="birthday">
                        <span>{{momentFormatter(birthday).format('DD-MM-YYYY')}}</span>
                    </span>
                    <span slot="type" slot-scope="type">
                        <span v-if="type === 1">Admin</span>
                        <span v-if="type === 2">Candidate</span>
                    </span>
                    <span slot="email_verified_at" slot-scope="text, record, index">
                        <span v-if="record.is_active"><a-tag color="blue">Yes</a-tag></span>
                        <span v-if="!record.is_active"><a-tag color="red">No</a-tag></span>
                    </span>
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
                },
                loading: false,
                columns: [
                    {
                        title: 'First name',
                        dataIndex: 'first_name',
                        width: 150,
                        sorter: true,
                    },
                    {
                        title: 'Last name',
                        dataIndex: 'last_name',
                        width: 150,
                        sorter: true,
                    },
                    {
                        title: 'Email',
                        dataIndex: 'email',
                        width: 150,
                        sorter: true,
                    },
                    {
                        title: 'Gender',
                        dataIndex: 'gender',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'gender' },
                    },
                    {
                        title: 'Birthday',
                        dataIndex: 'birthday',
                        width: 150,
                        sorter: true,
                        scopedSlots: { customRender: 'birthday' },
                    },
                    {
                        title: 'Type',
                        dataIndex: 'type',
                        width: 100,
                        sorter: true,
                        scopedSlots: { customRender: 'type' },
                    },
                    {
                        title: 'Is Active?',
                        dataIndex: 'email_verified_at',
                        width: 80,
                        sorter: true,
                        scopedSlots: { customRender: 'email_verified_at' },
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
                    first_name: null,
                    last_name: null,
                    email: null,
                    gender: '',
                    type: null,
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
                    url: `users`,
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
                        birthday: moment(response.data.birthday),
                        gender: response.data.gender.toString(),
                        status: response.data.is_active,
                        type: response.data.type.toString()
                    };
                    this.handleFormClick();
                })
                .catch((errors) => {

                })
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
