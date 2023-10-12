<template>
    <layout title="Data Pegawai">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

      <!-- modal -->
      <Dialog header="Form" v-model:visible="display" :breakpoints="{'960px': '75vw', '640px': '100vw'}" :style="{width: '50vw'}">
        <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Nama</label>
                <div class="col-md-8">
                  <InputText type="text" style="width: 100%!important" v-model="form.name"/>
                </div>
        </div>
         <div class="mb-3 row">
                <label class="col-md-4 col-form-label">email</label>
                <div class="col-md-8">
                  <InputText  type="text" style="width: 100%!important" v-model="form.email"/>
                </div>
              </div>
         <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Password</label>
                <div class="col-md-8">
                    <InputText type="password" style="width: 100%!important" v-model="form.password"/>
                </div>
              </div>

              <template #footer>
                    <Button label="Batal" icon="pi pi-times" class="p-button-text" @click="display = false"></Button>
                    <Button label="Simpan" icon="pi pi-check" autofocus @click="simpan"></Button>
                </template>


    </Dialog>

        <div class="card">
            <Toolbar class="p-mb-7">
                <template #left>
                    <Button label="Tambah" icon="pi pi-plus" class="p-button-sm p-button-sm" @click="tambah"></Button>
                </template>
            </Toolbar>

            <DataTable :value="dataUsers" paginator :rows="20" :rowsPerPageOptions="[5, 10, 20, 50]" :loading="loading"  tableStyle="min-width: 50rem">
                <Column field="" header="No">
                    <template #body="slotProps">
                        {{ ((lazyParams.page - 1)) + slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="name" header="Name"></Column>
                <Column field="email" header="Email"></Column>
                <Column field="">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="onEdit(slotProps.data)" style="margin-right: 10px;"/>
                        <Button @click="onDelete(slotProps.data)" icon="pi pi-trash" class="p-button-rounded p-button-warning"></Button>
                    </template>
                </Column>
                <template #empty>
                    No records found
                </template>
            </DataTable>



        </div>
    </layout>
</template>

<script>
import Layout from "../../Partials/Layout";
import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Toolbar from 'primevue/toolbar';
import RadioButton from 'primevue/radiobutton';
import InputText from 'primevue/inputtext';
import ColumnGroup from 'primevue/columngroup';
import {simpanusers, getusers, hapususers,} from '../../Api/datausers.api';


export default {
    name: "Data Users",
    components: {
        ErrorsAndMessages,
        Layout,
        Column,
        InputText,
        DataTable,
        RadioButton,
        Toolbar,
        Button,
        ColumnGroup,
    },

    data() {
        return {
            loading:false,
            search:'',
            display:false,
            filters: {},
            dataUsers:[],
            modalTitle:null,
            submitted: false,
            form:{
                name:null,
                email:null,
                password:null
            },
            initform:{
                name:null,
                email:null,
                password:null
            },
            lazyParams:{
                page:1
            },
            statuses: [
                {label: 'INSTOCK', value: 'instock'},
                {label: 'LOWSTOCK', value: 'lowstock'},
                {label: 'OUTOFSTOCK', value: 'outofstock'}
            ]
        }
    },
    methods:{
        async loadLazyData() {
            this.loading = true;
            const res = await getusers({page : this.lazyParams.page})
            console.log(res);

            this.dataUsers = res.data.data;
            this.loading = false;
        },
        onPage(event) {
            this.lazyParams.page = event.page + 1;
            this.loadLazyData();
        },
        tambah(){
            this.modalTitle = 'Tambah';
            this.display = true;
            Object.assign(this.form, this.initform);
        },
        async simpan(){
            await simpanusers(this.form);
            await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di simpan', life: 3000});
            await this.loadLazyData();
            this.display = false;

        },
        onDelete(data){
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    await hapususers({id:data.id});
                    await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di hapus', life: 3000});
                    await this.loadLazyData();
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        onEdit(data){
            this.modalTitle = 'Ubah';
            Object.assign(this.form, data);
            this.display = true;
        },
        onSearch() {
            this.dataTotal= 0;
            this.loadLazyData();
        },
    },

    mounted(){
        this.dataUsers = this.$page.props.users;
        this.dataTotal = this.$page.props.users.total;
        console.log(this.dataUsers);
    }

};
</script>
