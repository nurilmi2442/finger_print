<template>
    <layout title="DATA SITE">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

      <!-- modal -->
      <Dialog header="Header" v-model:visible="display" >
                <template #header>
                    <label>{{ modalTitle }}</label>
                </template>

                <div class="p-fluid">
                    <div class="p-field">
                        <label for="firstname">SITE</label>
                        <InputText v-model="form.id" type="text" hidden/>
                        <InputText v-model="form.nama" type="text" id="nama" />
                    </div>
                </div>
                <template #footer>
                    <Button label="Batal" icon="pi pi-times" class="p-button-text" @click="display = false"/>
                    <Button label="Simpan" icon="pi pi-check" autofocus @click="simpan"/>
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


            <DataTable :value="dataSites.data" :lazy="true" :paginator="true" :rows="dataSites.per_page" ref="dt"
            :totalRecords="dataSites.total" :loading="loading" @page="onPage($event)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
                <Column field="" header="No">
                    <template #body="slotProps">
                        {{ ((lazyParams.page - 1) * dataSites.per_page) + slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="nama" header="Site"></Column>
                <Column field="">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="onEdit(slotProps.data)" style="margin-right: 10px;"/>
                        <Button @click="onDelete(slotProps.data)" icon="pi pi-trash" class="p-button-rounded p-button-warning" />
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
import {getsites, simpansites, hapussites} from '../../Api/sites.api'


export default {
    name: "Sites",
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
            dataSites:[],
            search:'',
            display:false,
            filters: {},
            modalTitle:null,
            submitted: false,
            form:{
                id:null,
                nama:null
            },
            initform:{
                id:null,
                nama:null
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
            const res = await getsites({page : this.lazyParams.page, search: this.search})

            this.dataSites = res.data.data;
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
        onDelete(data){
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    await hapussites({id:data.id});
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

        async simpan(){
            await simpansites(this.form);
            await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di simpan', life: 3000});
            await this.loadLazyData();
            this.display = false;

        }
    },

    mounted(){
        this.dataSites = this.$page.props.site;
        this.dataTotal = this.$page.props.site.total;
        // console.log(this.$page.props.site.total);
    }

};
</script>
