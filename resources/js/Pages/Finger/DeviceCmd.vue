<template>
    <layout title="Device - Cmd">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

        <div class="card">
            <DataTable :value="Device.data" :lazy="true" :paginator="true" :rows="Device.per_page" ref="dt"
                :totalRecords="Device.total" :loading="loading" @page="onPage($event)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
                <Column field="" header="No">
                    <template #body="slotProps">
                        {{ ((lazyParams.page - 1) * Device.per_page) + slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="sn" header="Serial Number"></Column>
                <Column field="created_at" header="Tanggal"></Column>
                <Column field="command" header="command"></Column>
                <Column field="">
                    <template #body="slotProps">
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
import {getdevice,hapusdevice} from '../../Api/devicecmd.api';


export default {
    name: "DeviceCmd",
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
            Device:[],
            filters: {},
            modalTitle:null,
            submitted: false,

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
            const res = await getdevice({page : this.lazyParams.page})


            this.Device = res.data.data;
            this.loading = false;


        },
        onPage(event) {
            this.lazyParams.page = event.page + 1;
            this.loadLazyData();

        },
        onDelete(data){
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    await hapusdevice({id:data.id});
                    await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di hapus', life: 3000});
                    await this.loadLazyData();
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        }
    },

    mounted(){
        this.Device = this.$page.props.devicecmd;
    }

};
</script>
