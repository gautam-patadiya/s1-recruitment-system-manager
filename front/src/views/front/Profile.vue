<template>
    <div class="container">
        <a-row class="back-gray-2 wrapper">
            <a-col :xs="24" :sm="12" :md="6">
                <a-menu mode="vertical" class="mr-15 back-gray-1 sidebar" :defaultSelectedKeys="selectedSideItem">
                    <a-menu-item key="profile" @click="handleSidebarItemClick('profile')">Profile </a-menu-item>
                    <a-menu-item key="applied_jobs" @click="handleSidebarItemClick('applied_jobs')">Applied Jobs</a-menu-item>
                </a-menu>
            </a-col>
            <a-col :xs="24" :sm="12" :md="18">
                <a-row :gutter="16" v-if="selectedSideItem.includes('profile')">
                    <profile />
                    <a-col :sm="24" :md="24">
                        <h4>Upload Documents</h4>
                        <upload />
                        <table class="ant-table" v-if="docs && docs.length">
                            <thead class="ant-table-thead">
                                <tr>
                                    <th>#</th>
                                    <th>Document</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="ant-table-body">
                                <tr v-for="(doc, index) in docs" :key="index">
                                    <td>{{index + 1}}</td>
                                    <td>
                                        <a target="_blank" :href="doc.url">
                                            <small>
                                                <div>{{doc.document_type}}</div>
                                                <div>{{doc.filename}}</div>
                                            </small>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a-popconfirm
                                            placement="left"
                                            title="Sure to delete?"
                                            @confirm="() => handleDeleteDoc(doc.id)"
                                        >
                                            <a-button type="default" size="small">
                                                <a-icon type="delete" />
                                            </a-button>
                                        </a-popconfirm>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </a-col>
                </a-row>
                <a-row :gutter="16" v-if="selectedSideItem.includes('applied_jobs')">
                    <applied-job />
                </a-row>
            </a-col>
        </a-row>
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
                docs: []
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
