<template>
    <layout title="Daily Works">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

      <!-- modal -->
      <Dialog header="Form" v-model:visible="display" :breakpoints="{'960px': '75vw', '640px': '100vw'}" :style="{width: '50vw'}">
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Name</label>
                <div class="col-md-8">
                  <InputText type="text" style="width: 100%!important" v-model="form.nama" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Start in </label>
                <div class="col-md-8">
                  <InputText  type="time" style="width: 100%!important" v-model="form.start_masuk"/>
                </div>
              </div>
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label">In</label>
                <div class="col-md-8">
                  <InputText type="time"  style="width: 100%!important" v-model="form.masuk" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label">End in</label>
                <div class="col-md-8">
                  <InputText type="time"  style="width: 100%!important" v-model="form.end_masuk" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Start out</label>
                <div class="col-md-8">
                  <InputText type="time"  style="width: 100%!important" v-model="form.start_keluar" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Out</label>
                <div class="col-md-8">
                  <InputText type="time"  style="width: 100%!important" v-model="form.keluar" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label">End out</label>
                <div class="col-md-8">
                  <InputText type="time"  style="width: 100%!important" v-model="form.end_keluar" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Break in</label>
                <div class="col-md-8">
                  <InputText type="time"  style="width: 100%!important" v-model="form.break_masuk" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Break out</label>
                <div class="col-md-8">
                  <InputText type="time"  style="width: 100%!important" v-model="form.break_keluar" />
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
                    <Button label="Create" icon="pi pi-plus" class="p-button-sm p-button-sm" @click="tambah"></Button>
                </template>

                <template #right>
                    <InputText placeholder="Search..." v-model="search" style="font-size: 13px;" />
                    <Button icon="pi pi-search" iconPos="right" class="p-button-sm" @click="onSearch"></Button>
                </template>
            </Toolbar>


            <DataTable :value="dataShift.data" :lazy="true" :paginator="true" :rows="dataShift.per_page" ref="dt"
            :totalRecords="dataShift.total" :loading="loading" @page="onPage($event)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
                <Column field="" header="No">
                    <template #body="slotProps">
                        {{ ((lazyParams.page - 1) * dataShift.per_page) + slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="nama" header="Name"></Column>
                <Column field="start_masuk" header="Start in"></Column>
                <Column field="masuk" header="In"></Column>
                <Column field="end_masuk" header="End in"></Column>
                <Column field="start_keluar" header="Start out"></Column>
                <Column field="keluar" header="Out"></Column>
                <Column field="end_keluar" header="End out"></Column>
                <Column field="break_masuk" header="Break in"></Column>
                <Column field="break_keluar" header="Break out"></Column>
                <Column field="">
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
import InputText from 'primevue/inputtext';
import ColumnGroup from 'primevue/columngroup';
import {getdatashift, simpandatashift, hapusdatashift} from '../../Api/datashift.api';


export default {
    name: "Departemen",
    components: {
        ErrorsAndMessages,
        Layout,
        Column,
        InputText,
        DataTable,
        Toolbar,
        Button,
        ColumnGroup,
    },

    data() {
        return {
            loading:false,
            search:'',
            dataShift:[],
            display:false,
            filters: {},
            dataDepartemen:[],
            modalTitle:null,
            submitted: false,
            form:{
                nama:null,
                start_masuk:null,
                masuk:null,
                end_masuk:null,
                start_keluar:null,
                keluar:null,
                end_keluar:null,
                break_keluar:null,
                break_masuk:null,
            },
            initform:{
                nama:null,
                start_masuk:null,
                masuk:null,
                end_masuk:null,
                start_keluar:null,
                keluar:null,
                end_keluar:null,
                break_keluar:null,
                break_masuk:null,
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
            const res = await getdatashift({page : this.lazyParams.page, search: this.search})
            // console.log(res);

            this.dataShift = res.data.data;
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
            await simpandatashift(this.form);
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
                    await hapusdatashift({id:data.id});
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
        this.dataShift = this.$page.props.datashift;
        this.dataTotal = this.$page.props.datashift.total;
        // console.log(this.$page.props.site.total);
    }

};
</script>
