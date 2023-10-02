<template>
    <layout title="Schedule">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

      <!-- modal -->
      <Dialog header="Form" v-model:visible="display" :breakpoints="{'960px': '75vw', '640px': '100vw'}" :style="{width: '50vw'}">
         <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Nama Departemen </label>
                <div class="col-md-8">
                  <InputText  type="text" style="width: 100%!important" v-model="form.nama_departemen"/>
                </div>
              </div>
             <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Status</label>
                <div class="col-md-8">
                    <InputText type="text" style="width: 100%!important" v-model="form.status"/>
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

                <template #right>
                    <InputText placeholder="Search..." v-model="search" style="font-size: 13px;" />
                    <Button icon="pi pi-search" iconPos="right" class="p-button-sm" @click="onSearch"></Button>
                </template>
            </Toolbar>

            <DataTable>
                <Column field="" header="No">
                    <template #body="slotProps">
                        {{ ((lazyParams.page - 1) * dataMesin.per_page) + slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="pegawai.nama" header="Schedule"></Column>
                <Column field="nama" header="Departemen"></Column>
                <Column field="tanggal" header="Tanggal"></Column>
                <Column field="" header="Action">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="onEdit(slotProps.data)" style="margin-right: 10px;"/>
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
import RadioButton from 'primevue/radiobutton';
import InputText from 'primevue/inputtext';
import ColumnGroup from 'primevue/columngroup';
// import {getdepartemen, simpandepartemen, hapusdepartemen} from '../../Api/departemen.api';


export default {
    name: "Departemen",
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
            dataSites:[],
            search:'',
            display:false,
            filters: {},
            dataDepartemen:[],
            modalTitle:null,
            submitted: false,
            form:{
                id:null,
                nama_departemen:null,
                status:null
            },
            initform:{
                id:null,
                nama_departemen:null,
                status:null
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
            const res = await getdepartemen({page : this.lazyParams.page, search: this.search})

            this.dataDepartemen = res.data.data;
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
            await simpandepartemen(this.form);
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
                    await hapusdepartemen({id:data.id});
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
        this.dataDepartemen = this.$page.props.departemen;
        this.dataTotal = this.$page.props.departemen.total;
        // console.log(this.$page.props.site.total);
    }

};
</script>
