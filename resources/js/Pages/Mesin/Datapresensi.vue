<template>
    <layout title="Data Presensi">
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
        <div class="col">
            <label for="tanggal" class="control-label"
                style="display: block; margin-top: 1rem;">Start date :</label>
            <InputText style="width: 100%;" type="date" nama="start_date" v-model="form.start_date"/>
        </div>
        <div class="col">
            <label for="tanggal" class="control-label"
                style="display: block; margin-top: 1rem;">End date:</label>
            <InputText style="width: 100%;" type="date" nama="end_date" v-model="form.end_date" />
        </div>
        <div>
                <Button label="Process" style="display: block; margin-top: 3.2rem; margin-right: 10px;" class="p-button-sm p-button-sm" @click="proses"></Button>
        </div>
    </div>

        <div class="card">
            <DataTable :value="dataFingerDB.data" :lazy="true" :paginator="true" :rows="dataFingerDB.per_page" ref="dt"
            :totalRecords="dataFingerDB.total" :loading="loading" @page="onPage($event)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
                <Column field="nip" header="NIP"></Column>
                <Column field="date" header="Date"></Column>
                <Column field="ip_frontend" header="Machine"></Column>
                <template #empty>
                    No records found
                </template>

                <template #header>
                <div class="flex justify-content-end">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" ></i>
                        <InputText placeholder="Keyword Search" v-model="form.search"/>
                    </span>
                </div>
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
import {getdatapresensi, getfingerdatabase} from '../../Api/datapresensi.api';


export default {
    name: "Data Presensi",
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
            start_date:'',
            end_date:'',
            display:false,
            filters: {},
            dataMesin:[],
            dataSites:[],
            dataMesinAll:[],
            dataFingerDB:{
                data:[]
            },
            modalTitle:null,
            submitted: false,
            form:{
                id:null,
                ip:null,
                nama:null,
                id_sites:null,
                site:null,
                start_date:null,
                end_date:null,
                search:null,

            },
            initform:{
                id:null,
                ip:null,
                nama:null,
                id_sites:null,
                site:null,
                start_date:null,
                end_date:null,
                search:null,
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
            this.proses();

        },
        onPage(event) {
            this.lazyParams.page = event.page + 1;
            this.loadLazyData();
        },
        filterDatasite(){
            const filter = this.dataMesinAll.filter(x=>{
                return x.id_sites == this.form.site;
            })

            this.dataMesin = filter;
            console.log(filter);
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
          const data = await  getfingerdatabase({...this.form,...this.lazyParams});
          this.dataFingerDB= data.data.datafingerdb;
          this.loading = false;
        },
    },

    mounted(){
        this.dataSites = this.$page.props.site;
        this.dataMesin = this.$page.props.datamesin;
        this.dataMesinAll = this.dataMesin;

    }

};
</script>
