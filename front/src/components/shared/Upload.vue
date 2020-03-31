<template>
    <a-row :gutter="16" class="pl-5 pr-5">
        <a-col :xs="24" :sm="8" :md="8">
            <a-form-item :validate-status="(formErrors.has('document_type') ? 'error' : '')"
                         :help="formErrors.first('document_type')">
                <a-select placeholder="Select Document Type" allowClear v-model="document_type"
                          :dropdownMatchSelectWidth="false" style="width: 100%">
                    <a-select-option v-for="(document_type, index) in dropdowns.document_types" :key="index"
                                     :value="document_type.id">
                        {{document_type.label}}
                    </a-select-option>
                </a-select>
            </a-form-item>
        </a-col>
        <a-col :xs="24" :sm="14" :md="14">
            <a-form-item :validate-status="(formErrors.has('file') ? 'error' : '')" :help="formErrors.first('file')">
                <input type="file" name="file" class="ant-input" ref="file">
            </a-form-item>
        </a-col>
        <a-col :xs="12" :sm="2" :md="2">
            <a-button type="default" block @click="handleUpload()" :loading="uploading" :disabled="uploading"
                      title="Upload">
                <a-icon type="upload"/>
            </a-button>
        </a-col>
    </a-row>
</template>
<script>
    import {handleServerError, hasAuthUser} from "../../util/utils";
    import {request, client} from "../../util/request";
    import Error from "../../util/Error";
    import {uploadSuccess} from "../../util/notify";

    export default {
        data() {
            return {
                formErrors: new Error({}),
                dropdowns: {
                    document_types: []
                },
                uploading: false,
                files: [],
                document_type: undefined
            }
        },
        mounted() {
            if (!hasAuthUser()) {
                this.$router.push({name: 'login'});
                return false;
            }

            this.getDocumentTypes();
        },
        methods: {
            handleUpload() {
                const formData = new FormData();
                formData.append('file', ((this.$refs.file && this.$refs.file.files && this.$refs.file.files[0]) ? this.$refs.file.files[0] : ''));
                formData.append('document_type', (this.document_type ? this.document_type : ''));
                this.uploading = true;
                this.formErrors = new Error({});

                client.defaults.headers.common['Content-Type'] = 'multipart/form-data';
                request({
                    url: this.$router.resolve({name: 'uploadDocument'}).href,
                    method: 'post',
                    processData: false,
                    data: formData
                })
                    .then((response) => {
                        this.$refs.file.value = null;
                        this.document_type = undefined;
                        uploadSuccess();
                        this.$store.dispatch('refreshUploads');
                    })
                    .catch((errors) => {
                        if (errors.status && errors.status === 422) {
                            this.formErrors = new Error(errors.data.errors);
                        }

                        handleServerError(errors);
                        throw new Error("Something went badly wrong!");
                    })
                    .finally(() => {
                        this.uploading = false;
                    });
            },
            getDocumentTypes() {
                this.dropdowns.document_types = [];

                request({
                    method: "get",
                    url: `document/types`,
                })
                    .then((response) => {
                        this.dropdowns.document_types = response.data.map((item) => {
                            return {
                                id: item.id,
                                label: item.name
                            }
                        });
                    })
                    .catch((errors) => {

                    })
            },
        }
    }
</script>
