<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Otras Operaciones</h3>
        </div>
        <div class="card-body" v-loading="loading_submit">
            <div class="row">
                <div class="col-md-12 m-b-10">
                    <form autocomplete="off" @submit.prevent="deleteDocuments">
                        <div class="form-body">
                            <div class="row m-b-10">
                                <div class="col-md-4">
                                    <el-select v-model="form.period">
                                        <el-option key="all" value="all" label="Todo"></el-option>
                                        <el-option key="between" value="between" label="Entre fechas"></el-option>
                                    </el-select>
                                </div>
                                <template v-if="form.period === 'between'">
                                    <div class="col-md-4">
                                        <el-date-picker v-model="form.date_start" type="date"
                                                        value-format="yyyy-MM-dd"
                                                        format="dd/MM/yyyy"
                                                        :clearable="false"></el-date-picker>
                                    </div>
                                    <div class="col-md-4">
                                        <el-date-picker v-model="form.date_end" type="date"
                                                        value-format="yyyy-MM-dd"
                                                        format="dd/MM/yyyy"
                                                        :clearable="false"></el-date-picker>
                                    </div>
                                </template>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <el-button type="primary" native-type="submit">Eliminar documentos de prueba</el-button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <el-checkbox v-model="configuration.delete_document_demo" @change="changeDeleteDocumentDemo">Eliminar documentos de prueba en listado</el-checkbox>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                loading_submit: false,
                resource: 'options',
                errors: {},
                form: {},
                configuration: {}
            }
        },
        async created() {
            await this.initForm();
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.configuration = response.data.configuration
                })
        },
        methods: {
            initForm() {
                this.errors = {};
                this.form = {
                    period: 'all',
                    date_start: null,
                    date_end: null,
                };
                this.configuration = {
                    id: null,
                    delete_document_demo: null
                }
            },
            deleteDocuments() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}/delete_documents`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            changeDeleteDocumentDemo() {
                this.loading_submit = true;
                this.$http.post(`/${this.resource}/delete_document_demo`, this.configuration)
                    .then(response => {
                        this.$message.success(response.data.message)
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            }
        }
    }
</script>
