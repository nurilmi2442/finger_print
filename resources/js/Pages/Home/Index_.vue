<template>
<layout title="SO Contract">

 <DataTable :value="data.data" :lazy="true" :paginator="true" :rows="10" v-model:filters="filters" ref="dt"
            :totalRecords="data.total" :loading="loading" @page="onPage($event)" @sort="onSort($event)" @filter="onFilter($event)" filterDisplay="row"
            :globalFilterFields="['DocNo','ItName', 'Jum', 'Tgl','Uom']" responsiveLayout="scroll">
           <Column field="DocNo" header="SO Contract" filterMatchMode="startsWith" ref="DocNo" :sortable="true">  
                <template #filter="{filterModel,filterCallback}">
                    <input v-model="filterModel.value" @keydown.enter="filterCallback()" class="form-control" :placeholder="data.total">
                </template>                    
            </Column>
            <Column field="ItName" header="Item Name" filterField="ItName" filterMatchMode="contains" ref="ItName" :sortable="true">
                <template #filter="{filterModel,filterCallback}">
                     <input v-model="filterModel.value" @keydown.enter="filterCallback()" class="form-control" placeholder="search by name">
                </template>
            </Column>
            <Column field="Jum" header="Qty" filterField="Jum" filterMatchMode="contains" ref="Jum" :sortable="true">
                <template #filter="{filterModel,filterCallback}">
                     <input v-model="filterModel.value" @keydown.enter="filterCallback()" class="form-control" placeholder="search by name">
                </template>
            </Column>
            <Column field="Tgl" header="Date" filterField="Tgl" filterMatchMode="contains" ref="Tgl" :sortable="true">
                <template #filter="{filterModel,filterCallback}">
                     <input v-model="filterModel.value" @keydown.enter="filterCallback()" class="form-control" placeholder="search by name">
                </template>
            </Column>
             <Column field="Uom" header="Uom" filterField="Uom" filterMatchMode="contains" ref="Uom" :sortable="true">
                <template #filter="{filterModel,filterCallback}">
                     <input v-model="filterModel.value" @keydown.enter="filterCallback()" class="form-control" placeholder="search by name">
                </template>
            </Column>
              <Column field="Bom" header="Bom" filterField="Bom" filterMatchMode="contains" ref="Bom" :sortable="true">
                <template #filter="{filterModel,filterCallback}">
                     <input v-model="filterModel.value" @keydown.enter="filterCallback()" class="form-control" placeholder="search by name">
                </template>
            </Column>
        </DataTable>
</layout>
       
</template>

<script>
import AppHeader from "../../Partials/AppHeader";
import Footer from "../../Partials/Footer";
import Layout from "../../Partials/Layout";
import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {computed, inject} from "vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup'; 

export default {
    name: "Posts",
    components: {
        ErrorsAndMessages,
        AppHeader,
        Footer,
        Layout,
        DataTable,
        Column,
        ColumnGroup,
    },
   data() {
        return {
          data : [],
          filters: {
                'DocNo': {value: '', matchMode: 'contains'},
                'ItName': {value: '', matchMode: 'contains'},
                'Jum': {value: '', matchMode: 'contains'},
                'Tgl': {value: '', matchMode: 'contains'},
                'Uom': {value: '', matchMode: 'contains'},
                'Bom': {value: '', matchMode: 'contains'},
            },
            lazyParams: {},
            loading:false
        }
    },
    methods:{
        loadLazyData() {
            this.loading = true;
            axios.post('/so', this.lazyParams).then(response=>{
                 this.data = response.data;
                 this.loading = false;
            })
        },
        onPage(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        onSort(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        onFilter() {
            this.lazyParams.filters = this.filters;
            this.loadLazyData();
        }
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
