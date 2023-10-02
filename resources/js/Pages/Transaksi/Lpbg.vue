<template>
<layout title="LPBG">
    <Loading :loading="loadingData"></Loading>
    <ConfirmDialog></ConfirmDialog>
    <Dialog header="Form Data Tanah" v-model:visible="display" :breakpoints="{'960px': '75vw', '640px': '100vw'}" :style="{width: '50vw'}">
        <div class="row mb-2">
            <div class="col">
                 <InputText class="input" type="text" placeholder="Nomor LPBG" v-model="form.no_lpbg"/>
            </div>
        </div>
          <div class="row  mb-2">
            <div class="col">
                  <Calendar placeholder="Tanggal" class="input" id="basic" v-model="form.tanggal" autocomplete="off" :showIcon="true"/>
            </div>
        </div>
          <div class="row  mb-2">
            <div class="col">
                 <Dropdown v-model="selectedProyek"  :options="proyek" optionLabel="name" placeholder="Pilih Proyek" />
            </div>
        </div>
        
        
         <template #footer>
                    <Button label="Batal" icon="pi pi-times" class="p-button-text" @click="display = false"/>
                    <Button label="Simpan" icon="pi pi-check" autofocus @click="simpan"/>
                </template>
    </Dialog>

	 <div>
        <div class="card">
            <Toolbar class="p-mb-4">
                <template #left>
                    <Button label="Tambah" icon="pi pi-plus" class="p-button-success p-mr-2"  @click="display=true"/>
                </template>

                 <template #right>
                    <span class="p-input-icon-left">
                            <InputText  placeholder="Search..." v-model="search"/>
                            <Button icon="pi pi-search" iconPos="right" class="p-button-sm"  @click="onSearch"/>
                        </span>
                </template>
            </Toolbar>
           <DataTable :value="dataLpbg.data" :lazy="true" :paginator="true" :rows="dataLpbg.per_page" ref="dt"
            :totalRecords="dataLpbg.total" :loading="loading" @page="onPage($event)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
                  <template #empty>
                        No records found
                    </template>

                <Column field="no" header="No" :sortable="false" style="min-width:2rem">
                    <template #body="slotProps">
                         {{ ((lazyParams.page * dataLpbg.per_page) + slotProps.index ) + 1 }}
                    </template>
                </Column>
                <Column field="no_lpbg" header="Nomor LPBG" :sortable="false" style="min-width:16rem">
                     <template #body="slotProps">
                         
                        <a :href="$route('transaksi.edit_lpbg',{id: slotProps.data.id})">{{ slotProps.data.no_lpbg }}</a>
                    </template>
                </Column>
                <Column field="kode_proyek" header="Kode Proyek" :sortable="false" style="min-width:16rem"></Column>
                <Column field="tanggal_" header="Tanggal" :sortable="false" style="min-width:2rem"></Column>
                <Column :exportable="false" style="min-width:16rem">
                    <template #body="slotProps">
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
import { getFormattedDate } from '../../helpers';
import {Inertia} from "@inertiajs/inertia";
import {computed, inject} from "vue";
import { simpanLpbg, hapusLpbg, getLpbg } from '../../Api/transaksi.api';

export default {
    name: "Posts",
    components: {
        ErrorsAndMessages,
        Layout,
    },
   data() {
        return {
        dataLpbg:[],
        selectedProyek:[],
        proyek:[],
        search:null,
        form:{
            id:null,
            no_lpbg:null,
            tanggal:null,
            kode_proyek:null
            
        },
        initform:{
            id:null,
            no_lpbg:null,
            tanggal:null,
            kode_proyek:null
        },
        lazyParams: {
            page:0
        },
        loadingData:false,
        loading:false,
        display:false
        }
    },
    methods:{
        async loadLazyData() {
           this.loading = true;
            const res = await getLpbg({page : this.lazyParams.page + 1, search:this.search})

            this.dataLpbg = res.data.data;

            this.loading = false;
        },
        onSearch(){
            this.lazyParams.page = 0;
            this.loadLazyData();
        },
        onPage(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        onFilter() {
            this.lazyParams.filters = this.filters;
            this.loadLazyData();
        },
        edit(item){
           this.$inertia.visit(`/transaksi/lpbg/edit?id=${item.id}`);
        },
        onDelete(data){
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    await hapusLpbg({id:data.id});
                    await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di hapus', life: 3000});
                    await this.loadLazyData();
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        async simpan(){
            try{
                if(this.form.tanggal){
                    this.form.tanggal = await getFormattedDate(this.form.tanggal);
                }
                const response = await simpanLpbg(this.form)
                this.$inertia.visit(`/transaksi/lpbg/edit?id=${response.data.data.id}`);
            }
            catch(error){
                this.$toast.add({severity:'error', summary: 'Informasi!', detail:'Gagal Di simpan', life: 3000});
            }
           
        }
    },
    watch:{
        selectedProyek(val){
            this.form.kode_proyek = val.kode_proyek;
        }
    },
    setup() {
            
         // const data = computed(() => usePage().props.value.data);
    

        // return {
        //     data
        // }
    },
    mounted(){
        this.dataLpbg = this.$page.props.data.data;
        this.proyek = this.$page.props.proyek.data;
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

   .input{
        width: 100% !important;
   }

    ::v-deep(.p-autocomplete-input) {
        width: 100%!important;
    }

     ::v-deep(.p-dropdown) {
        width: 100%!important;
    }

</style>
