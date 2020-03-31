<template>
    <a-row>
        <a-col span="24">
            <div>
                <a-row>
                    <a-col :xs="24" :sm="24" :md="12">
                        <h2 class="mb-0">
                            All <strong>Companies</strong>
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
                            <a-col>
                                <a-form-item label="Email" class="mb-10">
                                    <a-input v-model="filters.email" />
                                </a-form-item>
                            </a-col>
                            <a-col>
                                <a-form-item label="Phone" class="mb-10">
                                    <a-input v-model="filters.phone" />
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
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Name" class="mb-10 required-input"
                                 :validate-status="(formErrors.has('name') ? 'error' : '')"
                                 :help="formErrors.first('name')">
                                    <a-input v-model="formFields.name" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Email" class="mb-10 required-input"
                                 :validate-status="(formErrors.has('email') ? 'error' : '')"
                                 :help="formErrors.first('email')">
                                    <a-input v-model="formFields.email" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Phone" class="mb-10 required-input"
                                 :validate-status="(formErrors.has('phone') ? 'error' : '')"
                                 :help="formErrors.first('phone')">
                                    <a-input v-model="formFields.phone" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="is active?"  class="mb-10">
                                    <a-checkbox :checked="formFields.status" v-model="formFields.status">
                                        Yes
                                    </a-checkbox>
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Additional Contact" class="mb-10"
                                 :validate-status="(formErrors.has('additional_contact') ? 'error' : '')"
                                 :help="formErrors.first('additional_contact')">
                                    <a-input v-model="formFields.additional_contact" />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12">
                                <a-form-item label="Additional Contact Number" class="mb-10"
                                     :validate-status="(formErrors.has('additional_contact_number') ? 'error' : '')"
                                     :help="formErrors.first('additional_contact_number')">
                                    <a-input v-model="formFields.additional_contact_number" />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row>
                            <a-col span="24">
                                <a-form-item label="Description" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('description') ? 'error' : '')"
                                     :help="formErrors.first('description')">
                                    <a-textarea v-model="formFields.description" autosize />
                                </a-form-item>
                            </a-col>
                            <a-col span="24">
                                <a-form-item label="Address" class="mb-10 required-input"
                                     :validate-status="(formErrors.has('address') ? 'error' : '')"
                                     :help="formErrors.first('address')">
                                    <a-textarea v-model="formFields.address" autosize />
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
                    class="fit-table companies-table"
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
                    <template slot="status" slot-scope="text, record, index" class="text-center">
                        <a-tag v-if="record.status !== 1" color="red">No</a-tag>
                        <a-tag v-if="record.status === 1" color="blue">Yes</a-tag>
                    </template>
                    <div slot="expandedRowRender" slot-scope="record">
                        <p><strong>Description:</strong> {{record.description}}</p>
                        <p><strong>Address:</strong> {{record.address}}</p>
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
                        title: 'Email',
                        dataIndex: 'email',
                        width: 150,
                        sorter: true,
                    },
                    {
                        title: 'Phone',
                        dataIndex: 'phone',
                        width: 150,
                        sorter: true,
                    },
                    {
                        title: 'Add. Contact',
                        dataIndex: 'additional_contact',
                        width: 150,
                        sorter: true,
                    },
                    {
                        title: 'Add. Contact No.',
                        dataIndex: 'additional_contact_number',
                        width: 150,
                        sorter: true,
                    },
                    {
                        title: 'is active?',
                        className: 'text-center',
                        dataIndex: 'status',
                        width: 150,
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
                    url: `companies`,
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
