<template>
    <layout title="Data User & Finger Print">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

    <div class="row" style="margin-bottom: 10px;">
        <div class="col">
            <label for="SITE" class="control-label"
                style="display: block; margin-top: 1rem;">Site:</label>
            <Dropdown v-model="form.site" :options="dataSites" @change="filterDatasite"
                optionLabel="nama" optionValue="id" placeholder="Select a site" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>
        <div class="col">
            <label for="IP Mesin" class="control-label"
                style="display: block; margin-top: 1rem;">IP Mesin :</label>
            <Dropdown v-model="form.ip" :options="dataMesin"
            optionLabel="ip" optionValue="id" placeholder="Select a ip address" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>
        <div>
                <Button label="Process" style="display: block; margin-top: 3.2rem; margin-right: 10px;" class="p-button-sm p-button-sm" @click="proses"></Button>
        </div>
        <div class="p-mb-4">
                <Button label="Synchronize User" style="display: block; margin-top: 3.2rem; margin-right: 10px;" class="p-button-sm p-button-sm" @click="synchronize"></Button>
        </div>
        <div class="p-mb-4">
                <Button label="Synchronize Finger Print" style="display: block; margin-top: 3.2rem; margin-right: 10px;" class="p-button-sm p-button-sm" @click="synchronize_finger"></Button>
        </div>
    </div>

    <Dialog header="Form" v-model:visible="display" :breakpoints="{'960px': '75vw', '640px': '100vw'}" :style="{width: '50vw'}">
        <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Name </label>
                <div class="col-md-8">
                  <InputText  type="text" style="width: 100%!important" v-model="form.name"/>
                </div>
              </div>

         <div class="mb-3 row">
                <label class="col-md-4 col-form-label">UserID </label>
                <div class="col-md-8">
                    <InputText  type="text" style="width: 100%!important" v-model="form.pin2"/>
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
                    <Button label="Upload user" icon="pi pi-plus" class="p-button-sm p-button-sm" @click="tambah"></Button>
                </template>
        </Toolbar>

            <DataTable :value="dataUserfinger" paginator :rows="20" :rowsPerPageOptions="[5, 10, 20, 50]" :loading="loading"  tableStyle="min-width: 50rem">
                <Column field="pin2" header="NIP"></Column>
                <Column field="name" header="Name"></Column>
                <Column field="template" header="Data finger"></Column>
                <Column field="">
                    <template #body="slotProps">
                        <Button @click="onDelete(slotProps.data)" icon="pi pi-trash" class="p-button-rounded p-button-warning" ></Button>
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
import RadioButton from 'primevue/radiobutton';//
import InputText from 'primevue/inputtext';
import ColumnGroup from 'primevue/columngroup';
import {getuserfinger, getuserfingerdatabase, simpanuploaduser , hapususer , synchronizefinger} from '../../Api/datauserfinger.api';

export default {
    name: "Data User & Finger",
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
            dataMesin:[],
            dataFinger:[],
            dataMesinAll:[],
            dataSites:[],
            dataUserfinger:[],
            modalTitle:null,
            submitted: false,
            form:{
                id:null,
                ip:null,
                name:null,
                id_sites:null,
                site:null,
                pin2:null,

            },
            initform:{
                id:null,
                ip:null,
                name:null,
                id_sites:null,
                site:null,
                pin2:null,
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
            const res = await getuserfinger({page : this.lazyParams.page, ...this.form})

        },
        tambah(){
            if (!this.form.nama && !this.form.ip){
            this.$toast.add({
                severity: 'error',
                summary: 'Kesalahan!',
                detail: 'Pilih Site dan IP sebelum melanjutkan.',
                life: 3000
            });
            return;
          }
            this.modalTitle = 'Tambah';
            this.display = true;
            this.form.name = null;
            this.form.pin2=null;
        },
        filterDatasite(){
            const filter = this.dataMesinAll.filter(x=>{
                return x.id_sites == this.form.site;
            })

            this.dataMesin = filter;
            console.log(filter);
        },
        onDelete(data){
            console.log(data);
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    await hapususer({pin2:data.pin2});
                    await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di hapus', life: 3000});
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        async simpan(){
            await simpanuploaduser(this.form);
            await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di simpan', life: 3000});
            this.display = false;
        },
        async proses(){
            if (!this.form.nama && !this.form.ip){
            this.$toast.add({
                severity: 'error',
                summary: 'Kesalahan!',
                detail: 'Pilih Site dan IP sebelum melanjutkan.',
                life: 3000
            });
            return;
          }
          this.loading = true;
          const data = await  getuserfingerdatabase(this.form);
          this.dataUserfinger= data.data.datauserfingerdb;
          this.loading = false;
        },
        async synchronize(){
          if (!this.form.nama && !this.form.ip){
            this.$toast.add({
                severity: 'error',
                summary: 'Kesalahan!',
                detail: 'Pilih Site dan IP sebelum melanjutkan.',
                life: 3000
            });
            return;
          }
          this.loading = true;
          const data = await  getuserfinger(this.form);
          await this.proses();
          this.loading = false;
          this.$toast.add({
                severity: 'success',
                summary: 'Informasi!',
                detail: 'Berhasil Di Tampilkan',
                life: 3000
          });

        },
        // async synchronize_finger(){
        //   if (!this.form.nama && !this.form.ip){
        //     this.$toast.add({
        //         severity: 'error',
        //         summary: 'Kesalahan!',
        //         detail: 'Pilih Site dan IP sebelum melanjutkan.',
        //         life: 3000
        //     });
        //     return;
        //   }
        //   this.loading = true;
        //   const data = await synchronizefinger(this.form);
        //   await this.proses();
        //   this.loading = false;
        //   this.$toast.add({
        //         severity: 'success',
        //         summary: 'Informasi!',
        //         detail: 'Berhasil Di Tampilkan',
        //         life: 3000
        //   });

        // },
    },
    mounted(){
        this.dataSites = this.$page.props.site;
        this.dataMesin = this.$page.props.datamesin;
        this.dataMesinAll = this.dataMesin;
        this.dataUserfinger = this.$page.props.datauserfingerdb;
        this.dataFinger = this.$page.props.data_finger;
    }
};
</script>
