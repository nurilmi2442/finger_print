<template>
<layout title="Cek Stok Barang">
       <ConfirmDialog></ConfirmDialog>
      <Toast position="top-center" />

   
    <div class="row">
        <div class="col">
             <Toolbar class="p-mb-4">
                <template #left>
                     <span class="p-input">
                            <InputText  placeholder="Search..." v-model="lazyParams.search" style="width: 400px;"/>
                            <Button @click="cari" class="p-button-raised p-button-success" icon="pi pi-search" />
                        </span>
                </template>
            </Toolbar>

            <DataTable :value="data.data" :lazy="true" :paginator="true" :rows="data.per_page" ref="dt"
            :totalRecords="data.total" :loading="loading" @page="onPage($event)" responsiveLayout="scroll">
            <Column field="" header="No">  
                <template #body="slotProps">
                     {{ (lazyParams.page * data.per_page) +  slotProps.index + 1 }}
                </template>
            </Column>
            <Column field="no_lpbg" header="No LPBG"></Column>
            <Column field="kode_proyek" header="Kode Proyek"></Column>
            <Column field="kode_material" header="Kode Material"></Column>
            <Column field="deskripsi" header="Deskripsi Material"></Column>
            <Column field="jml_msk" header="Jumlah Masuk"></Column>
            <Column field="jml_keluar" header="Jumlah Keluar">
                 <template #body="slotProps">
                     {{ slotProps.data.jml_keluar ? slotProps.data.jml_keluar : 0  }}
                </template>
            </Column>
            <Column field="saldo" header="Saldo"></Column>
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
import { getCekLpbg } from '../../Api/cek.api.js';
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
            search:null,
            loading:false,
            paramsSttp:{
                page:0,
                search:null
            }
        }
    },
    methods:{
        async loadLazyData() {
            this.loading = true;
            const res = await getCekLpbg({page:this.lazyParams.page + 1, search:this.lazyParams.search});
            this.data = res.data.data;
            this.loading = false;
        },
        cari(){
            this.paramsSttp.page = 0;
            this.loadLazyData();
        },
        onPage(event) {
            this.lazyParams.page = event.page;
            this.loadLazyData();
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
