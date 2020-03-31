<template>
    <div>
        <a-row :gutter="16" class="mb-15">
            <a-col>
                <h2 class="mb-0">
                    Candidate <strong>Overview</strong>
                </h2>
            </a-col>
        </a-row>
        <a-card title="Filters">
            <form @submit.prevent="loadList" autocomplete="off">
                <a-row>
                    <a-skeleton v-if="dropdowns.candidates.loading" active :paragraph="false" v-for="index in 1" :key="index"></a-skeleton>
                    <a-col :xs="24" :sm="24" :md="21" v-if="!dropdowns.candidates.loading">
                        <a-row :gutter="18">
                            <a-col :xs="24" :sm="8" :md="6">
                                <a-form-item label="Candidates" class="mb-0">
                                    <a-select v-model="filters.candidates" style="width: 100%" mode="multiple">
                                        <a-select-option v-for="(candidate, index) in dropdowns.candidates.dataSource"
                                                         :key="index" :value="candidate.id">
                                            {{candidate.label}}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="8" :md="6">
                                <a-form-item label="From Created At" class="mb-0">
                                    <a-date-picker v-model="filters.fromRegistered" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="8" :md="6">
                                <a-form-item label="To Created At" class="mb-0">
                                    <a-date-picker v-model="filters.toRegistered" format="DD-MM-YYYY" placeholder=""/>
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="16" :md="6">
                                <a-row :gutter="16">
                                    <a-col :xs="24" :sm="12" :md="12">
                                        <a-form-item label="Sort By" class="mb-0">
                                            <a-select v-model="filters.sortField" style="width: 100%" allowClear>
                                                <a-select-option value="first_name">Candidate FirstName</a-select-option>
                                                <a-select-option value="last_name">Candidate LastName</a-select-option>
                                                <a-select-option value="status">Is Active? </a-select-option>
                                            </a-select>
                                        </a-form-item>
                                    </a-col>
                                    <a-col :xs="24" :sm="12" :md="12">
                                        <a-form-item label="Sort Order" class="mb-0">
                                            <a-select v-model="filters.sortOrder" style="width: 100%" allowClear>
                                                <a-select-option value="desc">Descending</a-select-option>
                                                <a-select-option value="asc">Ascending</a-select-option>
                                            </a-select>
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="3" v-if="!dropdowns.candidates.loading">
                        <a-form-item label=" " class="mb-0 ml-15" :colon="false">
                            <a-button type="primary" html-type="submit">
                                <a-icon type="filter" /> Apply
                            </a-button>
                            <a href="javascript:;" class="ml-10" title="Export PDF" @click="handlePdfExportClick">
                                <a-icon type="file-pdf" />
                            </a>
                            <a href="javascript:;" class="ml-10" title="Export CSV" @click="handleCsvExportClick">
                                <a-icon type="file-excel" />
                            </a>
                        </a-form-item>
                    </a-col>
                </a-row>
            </form>
        </a-card>
        <a-card>
            <a-skeleton v-if="loading" active :paragraph="false" v-for="index in 15" :key="index"></a-skeleton>
            <div class="ant-table-wrapper" v-if="!loading">
                <a-table
                    class="fit-table report-candidates-table"
                    :columns="columns"
                    :rowKey="record => record.id"
                    :dataSource="dataSource"
                    :pagination="false"
                    :loading="loading">
                    <template slot="first_name" slot-scope="text, record, index">
                        {{record.first_name}} {{record.last_name}} <br>
                        <strong>Gender</strong>: {{(record.gender === 1 ? 'Male' : 'Female' )}} <br>
                        <strong>Email</strong>: {{record.email}}
                    </template>
                    <template slot="created_at" slot-scope="text, record, index">
                        {{(record.created_at ? covertDate(record.created_at, 'DD-MM-YYYY', 'YYYY-MM-DD') : '')}}
                    </template>
                    <template slot="job_applications" slot-scope="text, record, index">
                        {{(record.job_applications) ? record.job_applications.length : 0}}
                    </template>
                    <template slot="status" slot-scope="text, record, index">
                        <span v-if="record.status"><a-tag color="blue">Yes</a-tag></span>
                        <span v-if="!record.status"><a-tag color="red">No</a-tag></span>
                    </template>
                    <template slot="interviews" slot-scope="text, record, index">
                        {{(record.interviews) ? record.interviews.length : 0}}
                    </template>
                    <template slot="documents" slot-scope="text, record, index">
                        {{(record.documents) ? record.documents.length : 0}}
                    </template>
                </a-table>
            </div>
        </a-card>
    </div>
</template>

<script>
    import {request} from "../../util/request";
    import qs from "qs";
    import {dateToUtcDate, utcDateToLocalDate} from "../../util/utils";
    import mixins from "../../util/mixins";
    // @ is an alias to /src

    export default {
        mixins: [mixins],
        data() {
            return {
                loading: false,
                dataSource: [],
                columns: [
                    {
                        title: 'Candidate Name',
                        dataIndex: 'first_name',
                        width: 200,
                        scopedSlots: { customRender: 'first_name' },
                    },
                    {
                        title: 'Registered At',
                        dataIndex: 'created_at',
                        width: 200,
                        scopedSlots: { customRender: 'created_at' },
                    },
                    {
                        title: 'Is active?',
                        dataIndex: 'status',
                        width: 150,
                        scopedSlots: { customRender: 'status' },
                    },
                    {
                        title: 'Number of job applications',
                        dataIndex: 'job_applications',
                        width: 150,
                        scopedSlots: { customRender: 'job_applications' },
                    },
                    {
                        title: 'Number of Interviews',
                        dataIndex: 'interviews',
                        width: 150,
                        scopedSlots: { customRender: 'interviews' },
                    },
                    {
                        title: 'Number of Documents',
                        dataIndex: 'documents',
                        width: 150,
                        scopedSlots: { customRender: 'documents' },
                    },
                ],
                filters: {
                    fromRegistered: undefined,
                    toRegistered: undefined,
                    sortOrder: null,
                    sortField: null,
                    candidates: [],
                },
                dropdowns: {
                    candidates: {
                        loading: false,
                        dataSource: []
                    }
                }
            }
        },
        mounted() {
            // this.loadList();
            this.getCandidate();
        },
        methods: {
            loadList() {
                this.loading = true;
                const listQueryParams = {
                    filters: {
                        ...this.filters,
                        fromRegistered: ((this.filters.fromRegistered) ? dateToUtcDate(this.filters.fromRegistered, 'DD-MM-YYYY', 'YYYY-MM-DD') : ''),
                        toRegistered: ((this.filters.toRegistered) ? dateToUtcDate(this.filters.toRegistered, 'DD-MM-YYYY', 'YYYY-MM-DD') : ''),
                    },
                };

                request({
                    method: "get",
                    url: `reports/candidate/overview`,
                    params: listQueryParams,
                    paramsSerializer: ((listQueryParams) => qs.stringify(listQueryParams)),
                })
                    .then((response) => {
                        const {data} = response;
                        this.dataSource = data;
                    })
                    .catch(() => this.dataSource = [] )
                    .finally(() => this.loading = false);
            },
            covertDate(date, toFormat = 'DD-MM-YYYY hh:mm A', fromFormat = 'YYYY-MM-DD HH:mm:ss') {
                return utcDateToLocalDate(date, toFormat, fromFormat)
            },
            getCandidate() {
                this.dropdowns.candidates.loading = true;
                request({
                    method: "GET",
                    url: `users`,
                })
                .then((response) => {
                    this.dropdowns.candidates.dataSource = response.data.filter((item) => item.type > 1).map((item) => {
                        return {
                            id: item.id,
                            label: `${item.first_name} ${item.last_name}`,
                        }
                    })
                })
                .catch(() => {})
                .finally(() => this.dropdowns.candidates.loading = false);
            },
            handlePdfExportClick() {
                this.pdf.title = 'Candidate Overview';
                this.pdf.filename = 'candidate_overview';
                this.pdf.body = this.dataSource.map((item, index) => {
                    return {
                        ...this.exportFields(item),
                        index: (index + 1),
                    }
                });
                this.pdf.columns = [
                    {header: '#', dataKey: 'index'},
                    {header: 'Candidate Name', dataKey: 'candidate'},
                    {header: 'Registered At', dataKey: 'created_at'},
                    {header: 'Is Active?', dataKey: 'is_active'},
                    {header: 'Number of job applications', dataKey: 'number_of_job_application'},
                    {header: 'Number of interviews', dataKey: 'number_of_interviews'},
                    {header: 'Number of Documents', dataKey: 'number_of_documents'},
                ];

                this.exportPdf();
            },
            handleCsvExportClick() {
                this.csv.config.filename = 'Candidate Overview';
                this.csv.config.headers = ['Candidate Name', 'Registered At', 'Is Active?', 'Number of job applications', 'Number of Interviews', 'Number of Documents'];
                this.csv.dataSource = this.dataSource.map((item) => this.exportFields(item));
                this.exportCsv();
            },
            exportFields(item) {
                return {
                    candidate: `${item.first_name} ${item.last_name} || Gender: ${item.gender} || Email: ${item.email}`,
                    created_at: (item.created_at ? this.covertDate(item.created_at, 'DD-MM-YYYY', 'YYYY-MM-DD') : ''),
                    is_active: ((item.status) ? 'Yes' : 'No'),
                    number_of_job_application: ((item.job_applications) ? item.job_applications.length : 0),
                    number_of_interviews: ((item.interviews) ? item.interviews.length : 0),
                    number_of_documents: ((item.documents) ? item.documents.length : 0),
                }
            }
        },
        watch: {
        }
    }
</script>

<style lang="scss">
    @media screen and (max-width: 790px) {
        .fit-table.report-candidates-table td:before{ font-weight: bold; }
        .fit-table.report-candidates-table td:nth-of-type(1):before { content: "Candidate Name"; }
        .fit-table.report-candidates-table td:nth-of-type(2):before { content: "Registered At"; }
        .fit-table.report-candidates-table td:nth-of-type(3):before { content: "Is Active?"; }
        .fit-table.report-candidates-table td:nth-of-type(4):before { content: "Number of Job Apps."; }
        .fit-table.report-candidates-table td:nth-of-type(5):before { content: "Number of Interviews"; }
        .fit-table.report-candidates-table td:nth-of-type(6):before { content: "Number of Documents"; }
    }

    @media only screen and (min-width: 768px) {
        .info-num{
            text-align: right;
        }

    }

    @media only screen and (max-width: 992px) {
        .mt-sm-20{
            margin-top: 20px;
        }
    }
</style>
