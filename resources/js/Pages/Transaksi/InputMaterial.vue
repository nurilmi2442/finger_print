<template>
    <layout title="Home">
         <Toast position="top-center" />
         <ConfirmDialog></ConfirmDialog>
         <Dialog header="Header" v-model:visible="display" >
                    <template #header>
                        <label>Form Material</label>
                    </template>
    
                    <div class="p-fluid">
                        <div class="p-field">
                            <label for="firstname">Kode Material <span style="color:red;">*</span></label>
                            <InputText v-model="form.id" type="text" hidden/>
                            <InputText v-model="form.kode_material" type="text" :class="error && error.kode_material ? 'p-invalid' : '' "/>
                        </div>
                         <div class="p-field">
                            <label for="firstname">Deskripsi <span style="color:red;">*</span></label>
                            <InputText v-model="form.deskripsi" type="text"  :class="error && error.deskripsi ? 'p-invalid' : '' "/>
                        </div>
                         <div class="p-field">
                            <label for="firstname">Satuan <span style="color:red;">*</span></label>
                            <InputText v-model="form.satuan" type="text"  :class="error && error.satuan ? 'p-invalid' : '' "/>
                        </div>
                    </div>
                    <template #footer>
                        <Button label="Batal" icon="pi pi-times" class="p-button-text" @click="display = false"/>
                        <Button label="Simpan" icon="pi pi-check" autofocus @click="simpan"/>
                    </template>
                </Dialog>
    
        <div class="card">
            <Toolbar class="p-mb-4">
                    <template #left>
                        <Button label="Tambah" icon="pi pi-plus" class="p-button-success p-mr-2"  @click="tambah"/>
                    </template>
                    <template #right>
                        <span class="p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText  placeholder="Search..." />
                            </span>
                    </template>
                </Toolbar>
                <DataTable :value="data.data" :lazy="true" :paginator="true" :rows="data.per_page" ref="dt"
                :totalRecords="data.total" :loading="loading" @page="onPage($event)" responsiveLayout="scroll">
                <Column field="" header="No">  
                    <template #body="slotProps">
                         <!-- {{ ((lazyParams.page - 1) * data.per_page) +  slotProps.index + 1 }} -->
                         {{ ((lazyParams.page - 1) * dataPerPage) + slotProps.index + 1 + (dataPerPage * (lazyParams.page - 1)) }}
                    </template>
                </Column>
                <Column field="kode_material" header="Kode Material"></Column>
                <Column field="deskripsi" header="Deskripsi"></Column>
                <Column field="satuan" header="satuan"></Column>
                 <Column :exportable="false">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="edit(slotProps.data)" style="margin-right: 10px;"/>
                            <Button @click="onDelete(slotProps.data)" icon="pi pi-trash" class="p-button-rounded p-button-warning" />
                        </template>
                    </Column>
            </DataTable>
        </div>
        
    </layout>
           
    </template>
    
    <script>
    import Layout from "../../Partials/Layout";
    import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
    import {usePage} from "@inertiajs/inertia-vue3";
    import {Inertia} from "@inertiajs/inertia";
    import {computed, inject} from "vue";
    import { simpanMaterial, getMaterial, hapusMaterial } from '../../Api/transaksi.api.js';
    
    export default {
        name: "Posts",
        components: {
            ErrorsAndMessages,
            Layout,
        },
       data() {
            return {
              data : [],
              display:false,
              form:{
                id:null,
                kode_material:null,
                deskripsi:null,
                satuan:null
              },
              initform:{
                id:null,
                kode_material:null,
                deskripsi:null,
                satuan:null
              },
              error:{},
                lazyParams: {
                    page:1
                },
                loading:false
            }
        },
        methods:{
            async loadLazyData() {
                this.loading = true;
                const res = await getMaterial(this.lazyParams);
                this.data = res.data.data;
                this.loading = false;
            },
            edit(item){
                Object.assign(this.form, item)
                this.display = true;
            },
            onDelete(data){
                this.$confirm.require({
                    message: 'Yakin data akan di hapus?',
                    header: 'Confirmation',
                    icon: 'pi pi-exclamation-triangle',
                    accept: async () => {
                        await hapusMaterial({id:data.id});
                        await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di hapus', life: 3000});
                        await this.loadLazyData();
                    },
                    reject: () => {
                        //callback to execute when user rejects the action
                    }
                });
            },
            async simpan(){
                try{
                    this.error = {};
                    this.valid = true;
                    if(!this.form.kode_material){
                        this.error.kode_material = true;
                        this.valid = false;
                    }
                    if(!this.form.deskripsi){
                        this.error.deskripsi = true;
                        this.valid = false;
                    }
                    if(!this.form.satuan){
                        this.error.satuan = true;
                        this.valid = false;
                    }
                    if(this.valid){
                        const res = await simpanMaterial(this.form);
                         this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil di simpan', life: 3000});
                         this.display = false;
                         this.loadLazyData();
                    }
                }
                catch(error){
                    this.$toast.add({severity:'error', summary: 'Informasi!', detail:'Ada kesalahan server', life: 3000});
                }
                
    
    
    
            },
            tambah(){
                this.display = true;
                Object.assign(this.form,this.initform)
         
            },
            onPage(event) {
                this.lazyParams.page = event.page + 1;
                this.loadLazyData();
            },
        },
        props: {
            errors: Object
        },
        setup() {
                
             // const data = computed(() => usePage().props.value.data);
        
    
            // return {
            //     data
            // }
        },
        mounted(){
            this.data = this.$page.props.data;
    
        }
    }
    </script>
    
    <style scoped>
        .action-btn {
            margin-left: 12px;
            font-size: 13px;
        }
    
        .article {
            margin-top: 20px;
        }
    
        ::v-deep(.so-contract) {
        background-color: rgba(0,0,0,.15) !important;
    }
    
    </style>