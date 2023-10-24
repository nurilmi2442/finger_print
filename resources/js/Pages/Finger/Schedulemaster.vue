<template>
    <layout title="Working - Schedule">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

    <div class>
        <label for="tanggal" class="contro l-label" style="display: block;">Periode : </label>
        <div class="row">
                <div class="col-md-6" style="display:flex ;">
                    <div>
                        <InputText  type="date" nama="start_date" v-model="form.start_date"/>
                    </div>
                    <div style="margin-left: 10px;">
                        <div>
                            <span style="margin-right: 10px;">-</span>
                            <InputText  type="date" nama="end_date" v-model="form.end_date"/>
                        </div>
                    </div>
                </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label for="unit" class="control-label" style="display: block;  margin-top: 1rem;">Unit :</label>
            <Dropdown v-model="form.unit" :options="dataUnit"   optionLabel="unit"
                optionValue="id" placeholder="Select a Unit" :filter="true"  filterPlaceholder="Cari..." style="width: 57%;" />
            </div>
        </div>

        <div class="p-mb-6">
            <Button label="Process" style="display: block; margin-top: 1rem;" class="p-button-sm p-button-sm" @click="process"></Button>
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
                <Column field="nama_lengkap" header="NAMA "></Column>
                <Column field="nik" header="NIK"></Column>
                <Column field="nama_jabatan" header="JABATAN"></Column>
                <template v-for="(item,index) in daylist">
                    <Column :header="item" @click="onclickDate">
                        <template #body="slotProps">
                            <Dropdown :class="cekWeekend(item)"  @update:modelValue="(val)=>Onchangeshift(val,slotProps.data,item)" :modelValue="workSch(slotProps.data,item)" :options="dataShift"  optionLabel="nama" optionValue="id" placeholder="Shift"  style="width: 100%;" />
                        </template>
                    </Column>
                </template>

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
import {getschedulemaster, getpegawai, getworkingsch } from '../../Api/schedulemaster.api';


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
            search:'',
            display:false,
            filters: {},
            daylist:[],
            dataSchedulemaster:[],
            dataUnit:[],
            dataUnit2:[],
            dataShift:[],
            modalTitle:null,
            submitted: false,
            form:{
                unit:null,
                start_date:null,
                end_date:null,
                nama:null,
            },
            initform:{
                unit:null,
                start_date:null,
                end_date:null,
                nama:null,
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
        workSch(item, date){
           var a = item.workingsch.find(x=>x.tanggal == date);

           if(a){
            return parseInt(a.shift);
           }

        },
        cekWeekend(date){
            const myDate = new Date(date);

            if (myDate.getDay() === 6 || myDate.getDay() === 0)
            {
            return 'weekend';
            }
        },

        async loadLazyData() {
            this.loading = true;
            const res = await getschedulemaster({page : this.lazyParams.page})

            this.dataSchedulemaster = res.data.pegawai;
            this.loading = false;
        },
        onPage(event) {
            this.lazyParams.page = event.page + 1;
            this.loadLazyData();
        },
        getDaysArray(start, end) {
         for(var arr=[],dt=new Date(start); dt<=new Date(end); dt.setDate(dt.getDate()+1)){
        arr.push(this.formatDate( new Date(dt)));
         }
         return arr;
        },
        formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
        },
        async process(){
            if (!this.form.unit || !this.form.start_date || !this.form.end_date){
            this.$toast.add({
                severity: 'error',
                summary: 'Kesalahan!',
                detail: 'Pilih Periode dan Unit sebelum melanjutkan.',
                life: 3000
            });
            return;
          }
            const startDate = new Date(this.form.start_date);
            const endDate = new Date(this.form.end_date);

            if (startDate > endDate) {
                this.$toast.add({
                    severity: 'error',
                    summary: 'Kesalahan!',
                    detail: 'Start date tidak boleh lebih besar dari end date.',
                    life: 3000
                });
                return;
            }

            var daylist = this.getDaysArray(startDate, endDate);
            this.daylist=daylist;
            var unit = this.dataUnit2;
            const id = this.form.unit;
            this.loading = true;
            const data =await getpegawai({ id,...this.form });
            this.dataSchedulemaster = data.data.pegawai;
            this.loading = false;
        },
       async Onchangeshift(val,pgw,date){
            // cari row
            var a = this.dataSchedulemaster.data.findIndex(x=>x.id == pgw.id);

            var b = this.dataSchedulemaster.data[a].workingsch.findIndex(x=>x.tanggal == date);

            if(b < 0){
                var newData = {
                    id_pegawai : pgw.id,
                    tanggal :date,
                    shift : val
                }
                this.dataSchedulemaster.data[a].workingsch.push(newData);
            }
            else{
                this.dataSchedulemaster.data[a].workingsch[b].shift = val
            }

            this.loading = true;
            const params = {
                tanggal : date,
                id_pegawai:pgw.id,
                shift:val

            }
            const data =await getworkingsch(params);
            this.loading = false;
        },
        handleSubmit() {
        if (this.start_date > this.end_date) {
        this.$toasted.error('Start date cannot be greater than end date');
        } else {}
        }
    },

    mounted(){
        this.dataUnit = this.$page.props.masterunit;
        this.dataUnit2 = this.$page.props.masterunit;
        this.dataShift = this.$page.props.datashift;
        this.dataSchedulemaster = this.$page.props.pegawai;
    }
};
</script>

<style>
.weekend{
    background-color: #F0E68C;
}
</style>
