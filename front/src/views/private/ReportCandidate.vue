<template>
    <div>
        <b-row class="align-items-center job-header-simple">
            <b-col cols="12" md="6">
                <h2 class="job-header-title">
                    Candidate Overview
                </h2>
            </b-col>

            <b-col cols="12" md="6" class="text-md-right mt-15 mt-md-0">
                <b-button variant="outline-secondary" class="mr-10" @click="handleCsvExportClick">
                    <i class="bi bi-filetype-csv mr-5"></i>
                    Export CSV
                </b-button>
                <b-button variant="outline-secondary" @click="handlePdfExportClick">
                    <i class="bi bi-file-earmark-pdf mr-5"></i>
                    Export PDF
                </b-button>
            </b-col>
        </b-row>

        <b-card class="report-filter-card mb-20">
            <h5 class="report-filter-title">Filters</h5>
            <form @submit.prevent="loadList" autocomplete="off">
                <b-row>
                    <b-col cols="12" v-if="dropdowns.candidates.loading">
                        <b-skeleton v-for="index in 1" :key="index"></b-skeleton>
                    </b-col>
                    <b-col cols="12" v-if="!dropdowns.candidates.loading">
                        <b-row>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Candidates" class="mb-15">
                                    <v-select
                                        v-model="filters.candidates"
                                        :options="dropdowns.candidates.dataSource"
                                        label="label"
                                        :reduce="candidate => candidate.id"
                                        multiple
                                        placeholder="Select Candidates"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="From Created At" class="mb-15">
                                    <date-picker v-model="filters.fromRegistered" format="DD-MM-YYYY" value-type="format" placeholder="" />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="To Created At" class="mb-15">
                                    <date-picker v-model="filters.toRegistered" format="DD-MM-YYYY" value-type="format" placeholder="" />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Sort By" class="mb-15">
                                    <v-select
                                        v-model="filters.sortField"
                                        :options="sortFieldOptions"
                                        label="text"
                                        :reduce="option => option.value"
                                        placeholder="Sort By"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4">
                                <b-form-group label="Sort Order" class="mb-15">
                                    <v-select
                                        v-model="filters.sortOrder"
                                        :options="sortOrderOptions"
                                        label="text"
                                        :reduce="option => option.value"
                                        placeholder="Sort Order"
                                    ></v-select>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6" lg="4" class="d-flex align-items-end">
                                <b-form-group class="mb-15 w-100">
                                    <b-button variant="primary" type="submit">
                                        <i class="bi bi-check2-circle mr-5"></i>
                                        Apply Filters
                                    </b-button>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
            </form>
        </b-card>
        <b-card>
            <b-skeleton-table v-if="loading" :rows="15" :columns="columns.length"></b-skeleton-table>
            <div v-if="!loading">
                <b-table
                    class="fit-table report-candidates-table report-modern-table"
                    responsive
                    hover
                    show-empty
                    empty-text="No data available"
                    :fields="columns"
                    :items="dataSource"
                    :busy="loading"
                >
                    <template #cell(first_name)="data">
                        <div class="job-title-cell">
                            <div class="job-title-text">
                                {{data.item.first_name}} {{data.item.last_name}}
                            </div>
                            <div class="job-meta-line">
                                {{data.item.email}}
                            </div>
                            <b-badge class="user-light-badge mt-5">
                                {{(data.item.gender === 1 ? 'Male' : 'Female' )}}
                            </b-badge>
                        </div>
                    </template>
                    <template #cell(created_at)="data">
                        <span class="report-date-text">
                            {{(data.item.created_at ? covertDate(data.item.created_at, 'DD-MM-YYYY', 'YYYY-MM-DD') : '')}}
                        </span>
                    </template>
                    <template #cell(job_applications)="data">
                        <span class="report-count-pill">
                            {{(data.item.job_applications) ? data.item.job_applications.length : 0}}
                        </span>
                    </template>
                    <template #cell(status)="data">
                        <b-badge v-if="data.item.status" class="job-status-badge job-status-active">Active</b-badge>
                        <b-badge v-if="!data.item.status" class="job-status-badge job-status-inactive">Inactive</b-badge>
                    </template>
                    <template #cell(interviews)="data">
                        <span class="report-count-pill">
                            {{(data.item.interviews) ? data.item.interviews.length : 0}}
                        </span>
                    </template>
                    <template #cell(documents)="data">
                        <span class="report-count-pill">
                            {{(data.item.documents) ? data.item.documents.length : 0}}
                        </span>
                    </template>
                </b-table>
            </div>
        </b-card>
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
                        key: 'first_name',
                        label: 'Candidate Name',
                    },
                    {
                        key: 'created_at',
                        label: 'Registered At',
                    },
                    {
                        key: 'status',
                        label: 'Is active?',
                    },
                    {
                        key: 'job_applications',
                        label: 'Number of job applications',
                    },
                    {
                        key: 'interviews',
                        label: 'Number of Interviews',
                    },
                    {
                        key: 'documents',
                        label: 'Number of Documents',
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
                },
                sortFieldOptions: [
                    {value: 'first_name', text: 'Candidate FirstName'},
                    {value: 'last_name', text: 'Candidate LastName'},
                    {value: 'status', text: 'Is Active?'},
                ],
                sortOrderOptions: [
                    {value: 'desc', text: 'Descending'},
                    {value: 'asc', text: 'Ascending'},
                ],
            }
        },
        mounted() {
            // this.loadList();
            this.getCandidate();
        },
        methods: {
            loadList() {
                this.loading = true;
                const filters = {
                    ...this.filters,
                    fromRegistered: ((this.filters.fromRegistered) ? dateToUtcDate(this.filters.fromRegistered, 'DD-MM-YYYY', 'YYYY-MM-DD') : null),
                    toRegistered: ((this.filters.toRegistered) ? dateToUtcDate(this.filters.toRegistered, 'DD-MM-YYYY', 'YYYY-MM-DD') : null),
                };

                if (!filters.candidates.length) {
                    delete filters.candidates;
                }

                if (!filters.fromRegistered) {
                    delete filters.fromRegistered;
                }

                if (!filters.toRegistered) {
                    delete filters.toRegistered;
                }

                if (!filters.sortField) {
                    delete filters.sortField;
                }

                if (!filters.sortOrder) {
                    delete filters.sortOrder;
                }

                const listQueryParams = {
                    filters: filters,
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
