<template>
    <layout title="DATA MACHINE">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

      <!-- modal -->
      <Dialog header="Form" v-model:visible="display" :breakpoints="{'960px': '75vw', '640px': '100vw'}" :style="{width: '50vw'}">
        <div class="mb-3 row">
                <label class="col-md-4 col-form-label">IP Address</label>
                <div class="col-md-8">
                  <InputText type="text" style="width: 100%!important" v-model="form.ip"/>
                </div>
        </div>
         <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Mac Address</label>
                <div class="col-md-8">
                  <InputText  type="text" style="width: 100%!important" v-model="form.mac_address"/>
                </div>
              </div>
         <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Comkey</label>
                <div class="col-md-8">
                    <InputText type="number" style="width: 100%!important" v-model="form.comkey"/>
                </div>
              </div>
             <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Status</label>
                <div class="col-md-8">
                    <!-- <InputText type="text" style="width: 100%!important" v-model="form.status"/> -->
                    <div class="flex flex-wrap gap-3" style="display: flex;">
                        <div class="flex align-items-center" style="margin-left: 10px;">
                            <RadioButton v-model="form.status" inputId="ingredient1" name="pizza" value="1" />
                        <label for="ingredient1" class="ml-2">Aktif</label>
                    </div>
                    <div class="flex align-items-center" style="margin-left: 15px;">
                        <RadioButton v-model="form.status" inputId="ingredi" name="pizza" value="0" />
                        <label for="ingredient1" class="ml-2">Tidak Aktif</label>
                    </div>
                    </div>

                </div>
              </div>
               <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Site</label>
                <div class="col-md-8">
                    <Dropdown id="sites" :options="dataSites" optionLabel="nama" optionValue="id" placeholder="Select an item"  v-model="form.id_sites" style="width: 100%!important"/>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Location</label>
                <div class="col-md-8">
                  <InputText type="text" style="width: 100%!important" v-model="form.lokasi"/>
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

            <DataTable :value="dataMesin.data" :lazy="true" :paginator="true" :rows="dataMesin.per_page" ref="dt"
                :totalRecords="dataMesin.total" :loading="loading" @page="onPage($event)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
                <Column field="" header="No">
                    <template #body="slotProps">
                        {{ ((lazyParams.page - 1) * dataMesin.per_page) + slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="ip" header="IP Address"></Column>
                <Column field="mac_address" header="Mac Address"></Column>
                <Column field="comkey" header="Comkey"></Column>
                <Column field="status_nama" header="Status"></Column>
                <Column field="nama" header="Site"></Column>
                <Column field="lokasi" header="Location"></Column>
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
import ColumnGroup from 'primevue/columngroup';
import RadioButton from 'primevue/radiobutton';
import { getdatamesin, simpandatamesin, hapusdatamesin } from "../../Api/datamesin.api";


export default {
    name: "DataMesin",
    components: {
        ErrorsAndMessages,
        Layout,
        Column,
        DataTable,
        RadioButton,
        ColumnGroup,
    },

    data() {
        return {
            loading:false,
            dataSites:[],
            dataMesin:[],
            search:[],
            display:false,
            filters: {},
            modalTitle:null,
            submitted: false,
            datasite:[],
            form:{
                id:null,
                ip:null,
                mac_address:null,
                comkey:null,
                status:null,
                id_sites:null,
                lokasi:null
            },
            initform:{
                id:null,
                ip:null,
                mac_address:null,
                comkey:null,
                status:null,
                id_sites:null,
                lokasi:null

            },
            data : [],
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
            const res = await getdatamesin({page : this.lazyParams.page, search: this.search})
            console.log(res);

            this.dataMesin = res.data.data;
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
                    await hapusdatamesin({id:data.id});
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
            this.dataTotal = 0;
            this.loadLazyData();
        },
        async simpan(){
            await simpandatamesin(this.form);
            await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di simpan', life: 3000});
            await this.loadLazyData();
            this.display = false;

        }
    },

    mounted(){
        this.dataMesin = this.$page.props.datamesin;
        this.dataSites = this.$page.props.sites;
        this.dataTotal = this.$page.props.datamesin.total;

    }

};
</script>
