<template>
<layout title="Home">
       <ConfirmDialog></ConfirmDialog>
      <Toast position="top-center" />

    <Dialog header="Form" v-model:visible="display" :breakpoints="{'960px': '75vw', '640px': '100vw'}" :style="{width: '50vw'}">
        <div class="mb-3 row">
                <label class="col-md-4 col-form-label">No Sttp</label>
                <div class="col-md-8">
                  <InputText readonly placeholder="Generate otomatis oleh sistem" type="text" style="width: 100%!important" v-model="form.nosttp"/>
                </div>
        </div>
         <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Tanggal</label>
                <div class="col-md-8">
                  <InputText type="date" style="width: 100%!important" v-model="form.tanggal"/>
                </div>
              </div>
         <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Gedung</label>
                <div class="col-md-8">
                   <Dropdown id="gedung" :options="dataGedung" optionLabel="gedung" placeholder="Select an item"  v-model="selectedGedung" style="width: 100%!important" :filter="true" filterPlaceholder="Cari..."/>
                </div>
              </div>
             <div class="mb-3 row">
                <label class="col-md-4 col-form-label">Lokasi</label>
                <div class="col-md-8">
                  <Dropdown id="lokasi" :options="dataLokasi" optionLabel="lokasi" placeholder="Select an item"  v-model="selectedLokasi" style="width: 100%!important" :filter="true" filterPlaceholder="Cari..."/>
                </div>
              </div>
               <div class="mb-3 row">
                <label class="col-md-4 col-form-label">WBS</label>
                <div class="col-md-8">
                    <Dropdown id="project" :options="dataProject" optionLabel="name" placeholder="Select an item"  v-model="selectedProject" style="width: 100%!important" :filter="true" filterPlaceholder="Cari..."/>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-md-4 col-form-label">No. Kereta</label>
                <div class="col-md-8">
                  <InputText type="text" style="width: 100%!important" v-model="form.nomor_kereta"/>
                </div>
            </div>

              <template #footer>
                    <Button label="Batal" icon="pi pi-times" class="p-button-text" @click="display = false"/>
                    <Button label="Simpan" icon="pi pi-check" autofocus @click="simpan"/>
                </template>

    </Dialog>
    <div class="row">
        <div class="col">
             <Toolbar class="p-mb-4">
                <template #left>
                     <Button @click="tambah" label="Tambah" class="p-button-raised mb-2 p-button-success" icon="pi pi-plus" />
                </template>

                 <template #right>
                    <span class="p-input">
                            <InputText  placeholder="Search..." v-model="paramsSttp.search"/>
                            <Button @click="cari" class="p-button-raised p-button-success" icon="pi pi-search" />
                        </span>
                </template>
            </Toolbar>

            <DataTable :value="data.data" :lazy="true" :paginator="true" :rows="data.per_page" ref="dt"
            :totalRecords="data.total" :loading="loadingSttp" @page="onPageSttp($event)" responsiveLayout="scroll">
            <Column field="" header="No">
                <template #body="slotProps">
                     {{ (lazyParams.page * data.per_page) +  slotProps.index + 1 }}
                </template>
            </Column>
            <Column field="no_sttp" header="No STTP"></Column>
            <Column field="lokasi" header="Lokasi"></Column>
            <Column field="gedung" header="Gedung"></Column>
            <Column field="" header="WBS">
                 <template #body="slotProps">
                     {{ slotProps.data.pgcode + ' - ' + slotProps.data.pgname }}
                </template>
            </Column>
            <Column field="tanggal_" header="Tanggal"></Column>
             <Column style="width: 12vw !important;">
                    <template #body="slotProps">
                        <Link href="/endpoint" :headers="{ foo: bar }">Save</Link>
                        <Button icon="pi pi-print" class="p-button-rounded p-button-success p-mr-2" @click="print(slotProps.data)" style="margin-right: 10px;"/>
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="edit(slotProps.data)" style="margin-right: 10px;"/>
                        <Button @click="onDelete(slotProps.data)" icon="pi pi-trash" class="p-button-rounded p-button-warning" />
                    </template>
                </Column>
        </DataTable>
        </div>
    </div>
</layout>

</template>

<script>
import Layout from "../../Partials/Layout";
import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {computed, inject} from "vue";
import { getBpm, simpanSttp, deleteSttp, getSttp } from '../../Api/transaksi.api.js';
import { Link } from '@inertiajs/inertia-vue3';
export default {
    name: "Posts",
    components: {
        ErrorsAndMessages,
        Layout,
    },
   data() {
        return {
          data : [],
            lazyParams: {
                page:0,
                search:null
            },
            form:{
                id:null,
                gedung_id:null,
                lokasi_id:null,
                no_sttp:null,
                tanggal:null,
                kode_proyek:null,
                nomor_kereta:null
            },
            initForm:{
                id:null,
                gedung_id:null,
                lokasi_id:null,
                no_sttp:null,
                tanggal:null,
                kode_proyek:null,
                nomor_kereta:null
            },
            search:null,
            loadingSttp:false,
            dataProject:[],
            dataLokasi:[],
            dataGedung:[],
            loading:false,
            display:false,
            displayBpm:false,
            dataBpm:[],
            initBpm:[],
            selectedProject:[],
            selectedGedung:[],
            selectedLokasi:[],
            paramsSttp:{
                page:0,
                search:null
            }
        }
    },
    methods:{
        tambah(){
            this.display = true;
            this.form = this.initForm;
            this.selectedProject = [];
            this.selectedGedung = [];
            this.selectedLokasi = [];
        },
        async loadLazyData() {
            this.loadingSttp = true;
            const res = await getSttp({page:this.lazyParams.page + 1});
            this.data = res.data;
            this.loadingSttp = false;
        },
        cari(){
            this.paramsSttp.page = 0;
            this.loadSttp();
        },
        print(data){
            const params = {
                sttphdr_id : btoa(data.id),
            }
            var query = new URLSearchParams(params).toString();
            window.open('/cetak-pdf/sttp?' + query, '_blank');
        },
         onDelete(data){
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    await deleteSttp({id:data.id});
                    await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di hapus', life: 3000});
                    await this.loadSttp();
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        edit(item){
            this.$inertia.visit(`/transaksi/sttp/edit?id=${item.id}`);
        },
        onPageSttp(event) {
            this.lazyParams.page = event.page;
            this.loadLazyData();
        },
        async simpan(){
           try{
                const res = await simpanSttp(this.form);
               await this.$toast.add({severity:'success', summary: 'Information!', detail:'Data has been saved', life: 3000});
               this.display = false;
               this.loadLazyData();
           }
           catch(error){
             await this.$toast.add({severity:'error', summary: 'Information!', detail:'Data has been saved', life: 3000});
             this.display = false;
           }

        }
    },
    watch:{
        selectedGedung:function(val){
                this.form.gedung_id = val.id;
        },
        selectedLokasi:function(val){
                this.form.lokasi_id = val.id;
        },
         selectedProject:function(val){
                console.log(val);
                this.form.kode_proyek = val.kode_proyek;
            },
    },
    props: {
        errors: Object
    },
    setup() {

         // const data = computed(() => usePage().props.value.data);


        // return {
        //     data
        // }
    },
    mounted(){
        this.dataProject = this.$page.props.project;
        this.dataGedung = this.$page.props.gedung;
        this.dataLokasi = this.$page.props.lokasi;
        this.dataBpm = this.$page.props.bpm;
        this.initBpm = this.dataBpm
        this.data = this.$page.props.data;

    }
}
</script>

<style scoped>
    .action-btn {
        margin-left: 12px;
        font-size: 13px;
    }

    .article {
        margin-top: 20px;
    }

    ::v-deep(.so-contract) {
    background-color: rgba(0,0,0,.15) !important;
}

</style>
