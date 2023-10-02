<template>
<layout title="User">
	 <div>
         <ConfirmDialog></ConfirmDialog>
        <Toast position="top-right" />
        <!-- modal -->  
            <Dialog header="Header" v-model:visible="display" >
                <template #header>
                    <label>{{ modalTitle }}</label>
                </template>

                <div class="p-fluid">
                    <div class="p-field">
                        <label for="firstname">Nama</label>
                        <InputText v-model="form.id" type="text" hidden/>
                        <InputText v-model="form.name" type="text"/>
                    </div>
                    <div class="p-field">
                        <label for="firstname">NIP</label>
                        <InputText v-model="form.nip" type="text"/>
                    </div>
                     <div class="p-field">
                        <label for="firstname">Role</label>
                       <Dropdown v-model="selectedRole" :options="roles" optionLabel="name" placeholder="Pilih Role" />
                    </div>
                    <div class="p-field">
                        <label for="firstname">Password</label>
                        <InputText v-model="form.password" type="password"/>
                    </div>
                    <div class="p-field">
                        <label for="firstname">Confirm Password</label>
                        <InputText v-model="form.confirm_pass" type="password"/>
                    </div>
                </div>
                <template #footer>
                    <Button label="Batal" icon="pi pi-times" class="p-button-text" @click="display = false"/>
                    <Button label="Simpan" icon="pi pi-check" autofocus @click="simpan"/>
                </template>
            </Dialog>
        <!-- end modal -->
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

            <DataTable :value="dataUser.data" :lazy="true" :paginator="true" :rows="10" ref="dtUser"
            :totalRecords="100" :loading="loading" @page="onPage($event)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
                  <template #empty>
                        No records found
                    </template>

                <Column field="no" header="No" :sortable="false" style="min-width:2rem">
                    <template #body="slotProps">
                         {{ parseInt(((lazyParams.page * 10) + slotProps.index )) + 1 }}
                    </template>
                </Column>
                <Column field="name" header="Nama" :sortable="false" style="min-width:16rem"></Column>
                <Column field="nip" header="NIP" :sortable="false" style="min-width:16rem"></Column>
                <Column field="name_role" header="Role" :sortable="false" style="min-width:16rem"></Column>
                <Column :exportable="false">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="edit(slotProps.data)" style="margin-right: 10px;"/>
                        <Button @click="onDelete(slotProps.data)" icon="pi pi-trash" class="p-button-rounded p-button-warning" />
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</layout>
       
</template>

<script>
import Layout from "../../Partials/Layout";
import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {computed, inject} from "vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup'; 
import { simpanUser, getUser, hapusUser } from '../../Api/master.api';
export default {
    name: "master_lokasi",
    components: {
        ErrorsAndMessages,
        Layout,
        DataTable,
        Column,
        ColumnGroup,
    },
   data() {
        return {
            loading:false,
            dataUser:[],
            display:false,
            selectedRole: null,
            filters: {},
            modalTitle:null,
            submitted: false,
            roles:[
            	{
            		id:1,
            		name:'Admin'
            	},
            	{
            		id:2,
            		name:'User'
            	}
            ],
            form:{
                id:null,
                name:null,
                nip:null,
                role:null
            },
            lazyParams:{
                page:0,
                search:null
            },
        }
    },
    watch:{
    	selectedRole(val){
    		this.form.role = val.id;
    	}
    },
    methods:{
        async loadLazyData() {
            this.loading = true;
            const params = {
            	page:this.lazyParams.page + 1,
            	search:this.lazyParams.search
            }
            const res = await getUser(params)

            this.dataUser = res.data.data;

            this.loading = false;
        },
        onPage(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        tambah(){
            this.modalTitle = 'Tambah';
            this.display = true;
            this.lokasi = null;
        },
        onDelete(data){
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    await hapusUser({id:data.id});
                    await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di hapus', life: 3000});
                    await this.loadLazyData();
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        edit(data){
            this.modalTitle = 'Ubah';
            Object.assign(this.form, data);
            this.display = true;
        },
        async simpan(){
        	if(this.form.password !== this.form.confirm_pass){
        		this.$toast.add({severity:'info', summary: 'Informasi!', detail:'Password yang dimasukkan tidak sama', life: 3000, baseZIndex: 9999999});
        		return
        	}
            await simpanUser(this.form);
            await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di simpan', life: 3000});
            await this.loadLazyData();
            this.display = false;
            
        }
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
        this.dataUser = this.$page.props.data.data;
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
