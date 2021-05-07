<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Datos de la Empresa</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off" @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.number}">
                                <label class="control-label">Número</label>
                                <el-input v-model="form.number" :maxlength="11" :disabled="true"></el-input>
                                <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.name}">
                                <label class="control-label">Nombre</label>
                                <el-input v-model="form.name"></el-input>
                                <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.trade_name}">
                                <label class="control-label">Nombre comercial</label>
                                <el-input v-model="form.trade_name"></el-input>
                                <small class="form-control-feedback" v-if="errors.trade_name" v-text="errors.trade_name[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Logo</label>
                                <el-input v-model="form.logo" :readonly="true">
                                    <el-upload slot="append"
                                               :headers="headers"
                                               :data="{'type': 'logo'}"
                                               action="/companies/uploads"
                                               :show-file-list="false"
                                               :on-success="successUpload">
                                        <el-button type="primary" icon="el-icon-upload"></el-button>
                                    </el-upload>
                                </el-input>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.soap_type_id}">
                                <label class="control-label">SOAP Tipo</label>
                                <el-select v-model="form.soap_type_id">
                                    <el-option v-for="option in soap_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.soap_type_id" v-text="errors.soap_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.soap_username}">
                                <label class="control-label">SOAP Usuario</label>
                                <el-input v-model="form.soap_username"></el-input>
                                <small class="form-control-feedback" v-if="errors.soap_username" v-text="errors.soap_username[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" :class="{'has-danger': errors.soap_password}">
                                <label class="control-label">SOAP Password</label>
                                <el-input v-model="form.soap_password"></el-input>
                                <small class="form-control-feedback" v-if="errors.soap_password" v-text="errors.soap_password[0]"></small>
                            </div>
                        </div> 
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Formato A4</label>
                                <el-select v-model="form.format_a4" >
                                    <el-option key="standard" value="standard" label="Estándar"></el-option>
                                    <el-option key="franchise" value="franchise" label="Franquicia"></el-option> 
                                </el-select>
                            </div>
                        </div>
                        <div class="col-md-6" v-show="custom_ticket_format">
                            <div class="form-group">
                                <label class="control-label">Formato ticket</label>
                                <el-select v-model="form.ticket_width_mm" >
                                    <el-option key="74.1000" value="74.1000" label="Estándar"></el-option>
                                    <el-option key="80.0000" value="80.0000" label="80 mm"></el-option>
                                    <el-option key="50.0000" value="50.0000" label="50 mm"></el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right pt-2">
                    <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                loading_submit: false,
                headers: headers_token,
                resource: 'companies',
                errors: {},
                form: {},
                soap_types: [],
                custom_ticket_format:null
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.soap_types = response.data.soap_types
                    this.custom_ticket_format = response.data.custom_ticket_format 

                })
            await this.$http.get(`/${this.resource}/record`)
                .then(response => {
                    if (response.data !== '') {
                        this.form = response.data.data 
                    }
                })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    identity_document_type_id: '06000006',
                    number: null,
                    name: null,
                    trade_name: null,
                    soap_type_id: '01',
                    soap_username: null,
                    soap_password: null,
                    certificate: null,
                    logo: null,
                    ticket_width_mm: null,
                    format_a4: null,
                }
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
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
            successUpload(response, file, fileList) {
                if (response.success) {
                    this.$message.success(response.message)
                    this.form[response.type] = response.name
                } else {
                    this.$message({message:'Error al subir el archivo', type: 'error'})
                }
            },
        }
    }
</script>
