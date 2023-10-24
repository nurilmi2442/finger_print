<template>
    <layout title="Schedule - Master">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

    <div class="row">
        <div class="col">
            <label for="departemen" class="control-label"
                style="display: block; margin-top: 1rem;">Departemen :</label>
            <Dropdown v-model="form.nama_departemen" :options="dataDepartemen"  @change="filterData" optionLabel="nama_departemen"
                optionValue="id" placeholder="Select a Departemen" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>
        <div class="col">
            <label for="datapegawai" class="control-label"
                style="display: block; margin-top: 1rem;">Employee:</label>
            <Dropdown v-model="form.nama_pegawai" :options="dataPegawai" @change="filterData"
                optionLabel="nama" optionValue="id" placeholder="Select a name" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>
        <div class="col">
            <label for="datashift" class="control-label"
                style="display: block; margin-top: 1rem;">Shift:</label>
            <Dropdown v-model="form.nama_shift" :options="dataShift" @change="filterData"
                optionLabel="nama" optionValue="id" placeholder="Select a Shift" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>
        <div class="col">
            <label for="groupshift" class="control-label"
                style="display: block; margin-top: 1rem;">Group shift:</label>
            <Dropdown v-model="form.nama_groupshift" :options="dataGroupshift" @change="filterData"
                optionLabel="nama_group" optionValue="id" placeholder="Select a Group" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>
        <div class="col">
            <label for="tanggal" class="control-label"
                style="display: block; margin-top: 1rem;">Start date :</label>
            <InputText type="date" v-model="selectedProject" @input="filterData"/>
        </div>
        <div class="col">
            <label for="tanggal" class="control-label"
                style="display: block; margin-top: 1rem;">End date:</label>
            <InputText type="date" v-model="selectedProject" @input="filterData"/>
        </div>

        <div class="p-mb-6">
                <Button label="Process" style="display: block; margin-top: 3.2rem;" class="p-button-sm p-button-sm" @click="process"></Button>
        </div>
    </div>

        <div class="card" style="margin-top: 20px;">
            <DataTable :value="dataSchedulemaster.data" :lazy="true" :paginator="true" :rows="dataSchedulemaster.per_page" ref="dt"
            :totalRecords="dataSchedulemaster.total" :loading="loading" @page="onPage($event)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
                <Column field="" header="No">
                    <template #body="slotProps">
                        {{ ((lazyParams.page - 1) * dataSchedulemaster.per_page) + slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="nama" header="Nama"></Column>
                <Column field="nama_group" header="Shift"></Column>
                <Column field="selected_date" header="Tanggal"></Column>
                <Column field="">
                    <template #body="slotProps">
                        <!-- <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="onEdit(slotProps.data)" style="margin-right: 10px;"></Button> -->
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
import {getschedulemaster, hapusschedulemaster} from '../../Api/schedulemaster.api';


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
            dataSchedulemaster:[],
            dataDepartemen:[],
            dataGroupshift:[],
            dataShift:[],
            dataPegawai:[],
            modalTitle:null,
            submitted: false,
            form:{
                id:null,
                nama_departemen:null,
                nama_pegawai:null,
                nama_shift:null,
                nama_groupshift:null,
                tanggal:null
            },
            initform:{
                id:null,
                nama_departemen:null,
                nama_pegawai:null,
                nama_shift:null,
                nama_groupshift:null,
                tanggal:null
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
            const res = await getschedulemaster({page : this.lazyParams.page, ...this.form})

            this.dataSchedulemaster = res.data.schedulemaster;
            this.loading = false;
        },
        onPage(event) {
            this.lazyParams.page = event.page + 1;
            this.loadLazyData();
        },
        filterData(){
            this.totalData = 0;
            this.loadLazyData();
        },
        process(){
            this.modalTitle = 'process';
            this.display = true;
            Object.assign(this.form, this.initform);
        },
        onDelete(data){
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    await hapusschedulemaster({id:data.id});
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

    },

    mounted(){
        this.dataDepartemen = this.$page.props.departemen;
        this.dataPegawai = this.$page.props.datapegawai;
        this.dataGroupshift = this.$page.props.groupshift;
        this.dataShift = this.$page.props.datashift;
        this.dataSchedulemaster = this.$page.props.schedulemaster;
    }

};
</script>
