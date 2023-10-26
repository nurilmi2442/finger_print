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
import {getdevice} from '../../Api/devicecmd.api';


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
            // console.log(res);

            this.Device = res.data.devicecmd;
            this.loading = false;
        },
        onPage(event) {
            this.lazyParams.page = event.page + 1;
            this.loadLazyData();

        },
    },

    mounted(){
        this.Device = this.$page.props.devicecmd;
        console.log(this.Device);
    }

};
</script>
