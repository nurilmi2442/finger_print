<template>
    <layout title="Data Presensi">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

    <div class="row" style="margin-bottom: 10px;">
        <div class="col">
            <label for="IP Mesin" class="control-label"
                style="display: block; margin-top: 1rem;">IP Mesin :</label>
            <Dropdown v-model="form.ip" :options="dataMesin"  @change="filterData" optionLabel="ip"
                optionValue="id" placeholder="Select a ip address" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>
        <div class="col">
            <label for="SITE" class="control-label"
                style="display: block; margin-top: 1rem;">Site:</label>
            <Dropdown v-model="form.lokasi" :options="dataMesin" @change="filterData"
                optionLabel="lokasi" optionValue="id" placeholder="Select a site" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>
        <div class="col">
            <label for="tanggal" class="control-label"
                style="display: block; margin-top: 1rem;">Start date :</label>
            <InputText style="width: 100%;" type="date" v-model="selectedProject" @input="filterData"/>
        </div>
        <div class="col">
            <label for="tanggal" class="control-label"
                style="display: block; margin-top: 1rem;">End date:</label>
            <InputText style="width: 100%;" type="date" v-model="selectedProject" @input="filterData"/>
        </div>
        <div class="p-mb-4">
                <Button label="Synchronize" style="display: block; margin-top: 3.2rem; margin-right: 10px;" class="p-button-sm p-button-sm" @click="proses"></Button>
        </div>
    </div>


        <div class="card">
            <DataTable :value="dataPresensi" paginator :rows="20" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="pin" header="NIP"></Column>
                <Column field="datetime" header="DateTime"></Column>
                <Column field="verified" header="Verified"></Column>
                <Column field="status" header="Status"></Column>
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
import {getdatapresensi} from '../../Api/datapresensi.api';


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
            display:false,
            filters: {},
            dataMesin:[],
            dataPresensi:[],
            modalTitle:null,
            submitted: false,
            form:{
                id:null,
                ip:null,
                lokasi:null

            },
            initform:{
                id:null,
                ip:null,
                lokasi:null
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
            const res = await getdatapresensi({page : this.lazyParams.page, ...this.form})
        },
        filterData(){
            this.totalData = 0;
            this.loadLazyData();
        },
        async proses(){
          const data = await  getdatapresensi();
          this.dataPresensi = data.data.finger;
          console.log(this.dataPresensi, data);
        },
    },

    mounted(){
        this.dataPresensi = this.$page.props.datapresensi;
        this.dataMesin = this.$page.props.datamesin;
        console.log(this.$page.props);

    }

};
</script>
