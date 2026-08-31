<template>
    <div class="container profile-page">
        <b-row class="align-items-center job-header-simple">
            <b-col cols="12" md="7">
                <h2 class="job-header-title">
                    My Profile
                </h2>
            </b-col>
        </b-row>

        <b-row class="mt-20">
            <b-col cols="12" lg="3" class="mb-20 mb-lg-0">
                <div class="profile-menu-card">
                    <button
                        type="button"
                        class="profile-menu-link"
                        :class="{ active: selectedSideItem.includes('profile') }"
                        @click="handleSidebarItemClick('profile')"
                    >
                        <i class="bi bi-person-circle mr-10"></i>
                        Profile
                    </button>
                    <button
                        type="button"
                        class="profile-menu-link"
                        :class="{ active: selectedSideItem.includes('applied_jobs') }"
                        @click="handleSidebarItemClick('applied_jobs')"
                    >
                        <i class="bi bi-briefcase mr-10"></i>
                        Applied Jobs
                    </button>
                </div>
            </b-col>

            <b-col cols="12" lg="9">
                <b-row v-if="selectedSideItem.includes('profile')">
                    <b-col cols="12">
                        <profile />
                    </b-col>
                    <b-col cols="12">
                        <b-card class="profile-card mt-20">
                            <div class="profile-card-header">
                                <div>
                                    <h3>Upload Documents</h3>
                                    <p>Add resume, certificates, and other candidate documents.</p>
                                </div>
                            </div>

                            <upload />

                            <b-table
                                class="fit-table profile-document-table master-modern-table mt-20"
                                responsive
                                hover
                                show-empty
                                empty-text="No data available"
                                :items="docs"
                                :fields="documentFields"
                            >
                                <template #cell(index)="data">
                                    {{data.index + 1}}
                                </template>
                                <template #cell(document)="data">
                                    <a target="_blank" :href="data.item.url" class="profile-document-link">
                                        <span>{{data.item.document_type}}</span>
                                        <small>{{data.item.filename}}</small>
                                    </a>
                                </template>
                                <template #cell(action)="data">
                                    <div class="text-right">
                                        <b-button
                                            variant="outline-danger"
                                            size="sm"
                                            title="Delete"
                                            @click="confirmDeleteDoc(data.item.id)"
                                        >
                                            <i class="bi bi-trash mr-5"></i>
                                            Delete
                                        </b-button>
                                    </div>
                                </template>
                            </b-table>
                        </b-card>
                    </b-col>
                </b-row>
                <b-row v-if="selectedSideItem.includes('applied_jobs')">
                    <b-col cols="12">
                        <applied-job />
                    </b-col>
                </b-row>
            </b-col>
        </b-row>
    </div>
</template>
<script>
    import Profile from "../../components/modules/Profile";
    import AppliedJob from "../../components/modules/AppliedJob";
    import Upload from '../../components/shared/Upload';
    import {mapFields} from "vuex-map-fields";
    import {request} from "../../util/request";
    import {documentDeleted, serverError} from "../../util/notify";
    import {getAuthUser} from "../../util/utils";

    export default {
        components: {
            Profile,
            AppliedJob,
            Upload
        },
        data() {
            return {
                selectedSideItem: ['profile'],
                docs: [],
                documentFields: [
                    {
                        key: 'index',
                        label: '#',
                    },
                    {
                        key: 'document',
                        label: 'Document',
                    },
                    {
                        key: 'action',
                        label: 'Action',
                        thClass: 'text-right',
                        tdClass: 'text-right',
                    },
                ],
            }
        },
        mounted() {
            const user = getAuthUser();
            if(user.type != 2) {
                this.$router.push({
                    name: 'privateProfile'
                });
            }
        },
        methods: {
            handleSidebarItemClick(value) {
                this.selectedSideItem = [value];
            },
            confirmDeleteDoc(id) {
                if (window.confirm('Sure to delete?')) {
                    this.handleDeleteDoc(id);
                }
            },
            getUserOwnDocs() {
                request({
                    url: `/document/uploads/users`,
                })
                .then((response) => {
                    this.docs = response.data;
                })
                .catch(() => {

                })
            },
            handleDeleteDoc(id) {
                request({
                    method: "post",
                    url: `/document/uploads/delete`,
                    data: {
                        id: id
                    }
                })
                    .then((response) => {
                        documentDeleted();
                        this.getUserOwnDocs();
                    })
                    .catch((errors) => {
                        serverError();
                    })
            },
        },
        computed: {
            ...mapFields([
                'uploads'
            ]),
        },
        watch: {
            'uploads': {
                immediate: true,
                handler(newVal) {
                    this.getUserOwnDocs();
                }
            }
        }
    }
</script>
<style lang="scss">
    .profile-page {
        padding-top: 20px;
        padding-bottom: 30px;
    }

    .profile-menu-card,
    .profile-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
    }

    .profile-menu-card {
        padding: 10px;
    }

    .profile-menu-link {
        align-items: center;
        background: transparent;
        border: 0;
        border-radius: 12px;
        color: #475569;
        display: flex;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 6px;
        padding: 12px 14px;
        text-align: left;
        width: 100%;
    }

    .profile-menu-link.active,
    .profile-menu-link:hover {
        background: #eff6ff;
        color: #2563eb;
    }

    .profile-card-header {
        align-items: flex-start;
        display: flex;
        gap: 15px;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .profile-card-header h3 {
        color: #0f172a;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .profile-card-header p {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 0;
    }

    .profile-password-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        margin-top: 10px;
        padding: 18px;
    }

    .profile-password-box h5 {
        color: #0f172a;
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .profile-password-box p {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 16px;
    }

    .profile-card .profile-date-picker,
    .profile-card .mx-datepicker {
        width: 100%;
    }

    .profile-document-link {
        display: inline-flex;
        flex-direction: column;
        gap: 3px;
    }

    .profile-document-link span {
        color: #0f172a;
        font-weight: 700;
    }

    .profile-document-link small {
        color: #64748b;
    }

    @media (max-width: 767px) {
        .profile-card-header {
            display: block;
        }

        .profile-card-header .btn {
            margin-top: 12px;
            width: 100%;
        }
    }
</style>
