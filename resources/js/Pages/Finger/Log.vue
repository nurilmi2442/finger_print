<template>
    <layout title="LOG">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

    <div class="row" style="margin-bottom: 10px;">
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

            <DataTable :value="Log.data" :lazy="true" :paginator="true" :rows="Log.per_page" ref="dt"
            :totalRecords="Log.total" :loading="loading" @page="onPage($event)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
                <Column field="sn" header="Serial Number"></Column>
                <Column field="ip_mesin" header="IP"></Column>
                <Column field="site" header="Site"></Column>
                <Column field="data" header="Data"></Column>
                <Column field="date" header="Date"></Column>
                <Column field="url" header="URL"></Column>
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
import {getlog} from '../../Api/log.api';


export default {
    name: "Log",
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
            Log:[],
            modalTitle:null,
            submitted: false,
            form:{
                start_date:null,
                end_date:null,
                search:null,

            },
            initform:{
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
            const res = await getlog({page : this.lazyParams.page})
            console.log(res);

            this.Log = res.data.log;
            this.loading = false;
        },
        onPage(event) {
            this.lazyParams.page = event.page + 1;
            this.loadLazyData();
        },
        async proses(){
          this.loading = true;
          const data = await  getlog({...this.form,...this.lazyParams});
          this.Log= data.data.log;
          this.loading = false;
        },

    },

    mounted(){
        this.Log = this.$page.props.log;
        this.log
    }

};
</script>
