<template>
    <quill-editor
        ref="myRef"
        :options="(toolBar > 1 ? editorToolbarOptionsImage : editorToolbarOptions)"
        v-model="content"
        @change="onEditorChange($event)">
    </quill-editor>
</template>
<script>
    import {quillEditor} from 'vue-quill-editor';
    import Quill from 'quill';
    import 'quill/dist/quill.snow.css';
    import {ImageDrop} from 'quill-image-drop-module';
    import ImageResize from 'quill-image-resize-module';
    import quillTable from 'quill-table';
    import {unknownError, uploadSuccess} from "../../util/notify";
    import {client, request} from "../../util/request";
    import Error from "../../util/Error";
    import {handleServerError} from "../../util/utils";

    /** To Fix Image Position Issue **/
    // const Parchment = Quill.import('parchment');
    const BaseImageFormat = Quill.import('formats/image');
    const ImageFormatAttributesList = ['alt', 'height', 'width', 'style'];

    class ImageFormat extends BaseImageFormat {
        static formats(domNode) {
            return ImageFormatAttributesList.reduce(function (formats, attribute) {
                if (domNode.hasAttribute(attribute)) {
                    formats[attribute] = domNode.getAttribute(attribute);
                }
                return formats;
            }, {});
        }

        format(name, value) {
            if (ImageFormatAttributesList.indexOf(name) > -1) {
                if (value) {
                    this.domNode.setAttribute(name, value);
                } else {
                    this.domNode.removeAttribute(name);
                }
            } else {
                super.format(name, value);
            }
        }
    }

    Quill.register(ImageFormat, true);
    /** To allow Image Upload**/
    Quill.register('modules/imageDrop', ImageDrop);
    Quill.register('modules/imageResize', ImageResize);
    Quill.register(quillTable.TableCell);
    Quill.register(quillTable.TableRow);
    Quill.register(quillTable.Table);
    Quill.register(quillTable.Contain);
    Quill.register('modules/table', quillTable.TableModule);


    export default {
        props: ['propsContent', 'propsToolBar', 'propsUploadUrl', 'propsEventEmit', 'propsDisabled'],
        components: {
            quillEditor
        },
        data() {
            let base = this;

            const tableOptions = [];
            for (let r = 1; r <= 10; r++) {
                for (let c = 1; c <= 5 ; c++) {
                    tableOptions.push('newtable_' + r + '_' + c);
                }
            }

            return {
                toolBar: ((this.propsToolBar && this.propsToolBar === '2') ? 2 : 1),
                editorToolbarOptions: {
                    modules: {
                        toolbar: {
                            container: [
                                [{'header': [false, 1, 2, 3, 4, 5, 6]}],
                                [{'size': ['small', false, 'large']}],
                                ['bold', 'italic', 'underline', 'strike', {'indent': '-1'}, {'indent': '+1'}],
                                [{'list': 'ordered'}, {'list': 'bullet'}],
                            ],
                        },
                        history: {
                            delay: 1000,
                            maxStack: 50,
                            userOnly: false
                        },
                    },
                    theme: 'snow'
                },
                editorToolbarOptionsImage: {
                    modules: {
                        toolbar: {
                            container: [
                                [{'header': [false, 1, 2, 3, 4, 5, 6]}],
                                [{'size': ['small', false, 'large']}],
                                ['bold', 'italic', 'underline', 'strike', 'code-block', {'indent': '-1'}, {'indent': '+1'},{ 'direction': 'rtl' },{ 'align': [] }],
                                [{'list': 'ordered'}, {'list': 'bullet'}],
                                ['link', 'image', 'video'],
                                [{table: tableOptions}, {table: 'append-row'}, {table: 'append-col'}],
                                // ["showHtml"],
                            ],
                            handlers: {
                                image: function () {
                                    let fileInput = this.container.querySelector('input.ql-image[type=file]');
                                    if (fileInput == null) {
                                        fileInput = document.createElement('input');
                                        fileInput.setAttribute('type', 'file');
                                        fileInput.setAttribute(
                                            'accept', 'image/png, image/gif, image/jpeg, image/bmp, image/x-icon'
                                        );
                                        fileInput.classList.add('ql-image');
                                        fileInput.addEventListener('change', () => {
                                            if (fileInput.files != null && fileInput.files[0] != null) {
                                                base.handleImageAdded(fileInput, this);
                                            }
                                        });
                                        this.container.appendChild(fileInput);
                                    }
                                    fileInput.click();
                                },
                                showHtml: () => {
                                    if (this.txtArea.style.display === "") {
                                        const html = this.txtArea.value;
                                        if (html === '<p><br/></p>') {
                                            this.html = null;
                                        } else {
                                            this.html = html.replace(new RegExp('<p><br/>', 'g'), '<p>')
                                        }
                                        this.quill.pasteHTML(html);
                                    }

                                    this.txtArea.style.display =
                                        this.txtArea.style.display === "none" ? "" : "none";
                                }
                            }
                        },
                        table: true,
                        history: {
                            delay: 1000,
                            maxStack: 50,
                            userOnly: false
                        },
                        imageDrop: true,
                        imageResize: {
                            displayStyles: {
                                backgroundColor: 'black',
                                border: 'none',
                                color: 'white'
                            },
                            modules: ['Resize', 'DisplaySize', 'Toolbar']
                        },
                    },
                    theme: 'snow'
                },
                numberOfAttachments: [],
                url: this.propsUploadUrl,
                content: '',
                quill: "",
            }
        },
        watch: {
            propsDisabled: {
                immediate: true,
                handler(newVal) {
                    if(this.$refs.myRef && this.$refs.myRef.quill) {
                        this.$refs.myRef.quill.enable(false);
                        if(newVal === true){
                            this.$refs.myRef.quill.enable(true);
                        }
                    }
                }
            },
            propsContent: {
                immediate: true,
                handler(newVal) {
                    this.content = newVal;
                }
            },
        },
        mounted: function () {
            this.quill = this.$refs.myRef.quill;
            this.txtArea = document.createElement("textarea");
            this.txtArea.style.cssText =
                "width: 100%;margin: 0px;background: rgb(29, 29, 29);box-sizing: border-box;color: rgb(204, 204, 204);font-size: 15px;outline: none;padding: 20px;line-height: 24px;font-family: Consolas, Menlo, Monaco, &quot;Courier New&quot;, monospace;position: absolute;top: 0;bottom: 0;border: none;display:none;resize: none;";

            const htmlEditor = this.quill.addContainer("ql-custom");
            htmlEditor.appendChild(this.txtArea);
            this.quill.on("text-change", (delta, oldDelta, source) => {
                var html = this.quill.container.firstChild.innerHTML;
                this.txtArea.value = html;
            });

            this.content = this.propsContent;
        },
        methods: {
            onEditorChange({quill, html, text}) {
                this.$emit('update:propsContent', html);
            },

            handleImageAdded(fileInput, instance) {
                let formData = new FormData();
                formData.append('file', fileInput.files[0]);

                client.defaults.headers.common['Content-Type'] = 'multipart/form-data';
                request({
                    method: 'post',
                    url: this.propsUploadUrl,
                    processData: false,
                    data: formData
                })
                    .then((response) => {
                        if (response.data && response.data.url !== undefined) {
                            instance.quill.editor.insertEmbed(
                                instance.quill.getSelection().index,
                                'image',
                                response.data.url
                            );

                            instance.quill.insertText(-1, ' ');
                            this.numberOfAttachments.push({id: response.data.id});
                        } else {
                            unknownError();
                        }
                    })
                    .catch((errors) => {
                        if (errors.status && errors.status === 422) {
                            this.formErrors = new Error(errors.data.errors);
                        }

                        handleServerError(errors);
                        throw new Error("Something went badly wrong!");
                    })
                    .finally(() => {
                        // if (this.propsEventEmit !== undefined) {
                        //     this.$root.$emit(this.propsEventEmit, this.numberOfAttachments);
                        // }
                    });
            },
        },
    }
</script>
<style lang="scss">
    .ql-editor{
        font-weight: initial;
    }

    .ql-formats{
        background: rgba(226, 226, 226, 0.39);
    }

    .ql-showHtml{
        padding-top: 1px !important;

        &:after{
            content: 'S';
        }
    }
</style>
