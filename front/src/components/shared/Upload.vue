<template>
    <b-row class="pl-5 pr-5">
        <b-col cols="12" sm="4" md="4">
            <b-form-group>
                <v-select
                    v-model="document_type"
                    :options="dropdowns.document_types"
                    label="label"
                    :reduce="documentType => documentType.id"
                    :class="{ 'is-invalid': formErrors.has('document_type') }"
                    placeholder="Select Document Type"
                ></v-select>
                <div class="invalid-feedback d-block" v-if="formErrors.has('document_type')">
                    {{formErrors.first('document_type')}}
                </div>
            </b-form-group>
        </b-col>
        <b-col cols="12" sm="7" md="7">
            <b-form-group>
                <input
                    type="file"
                    name="file"
                    class="form-control-file"
                    :class="{ 'is-invalid': formErrors.has('file') }"
                    ref="file"
                >
                <div class="invalid-feedback d-block" v-if="formErrors.has('file')">
                    {{formErrors.first('file')}}
                </div>
            </b-form-group>
        </b-col>
        <b-col cols="6" sm="1" md="1">
            <b-button
                variant="outline-secondary"
                block
                @click="handleUpload()"
                :disabled="uploading"
                title="Upload"
            >
                <b-spinner v-if="uploading" small></b-spinner>
                <i v-else class="bi bi-upload"></i>
            </b-button>
        </b-col>
    </b-row>
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
