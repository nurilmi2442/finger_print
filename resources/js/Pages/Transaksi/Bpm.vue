<template>
<layout title="Home">
	<DataTable :value="data.data" :lazy="true" :paginator="true" :rows="10" ref="dt"
            :totalRecords="data.total" :loading="loading" @page="onPage($event)" responsiveLayout="scroll">
            <Column field="" header="No">  
                <template #body="slotProps">
                     {{ (lazyParams.page * 10) +  slotProps.index + 1 }}
                </template>
            </Column>
            <Column field="document_number" header="Document Number"></Column>
            <Column field="document_date" header="Document Date"></Column>
            <Column field="lpbg" header="LPBG"></Column>
            <Column field="item_number" header="Item Number"></Column>
            <Column field="material_code" header="Material Code"></Column>
            <Column field="qty" header="Qty"></Column>
            <Column field="uom" header="Uom"></Column>
            <Column field="destination_shop" header="Destination Shop"></Column>
        </DataTable>
</layout>
       
</template>

<script>
import Layout from "../../Partials/Layout";
import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {computed, inject} from "vue";
import { getBpm } from '../../Api/transaksi.api.js';

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
                page:0
            },
            loading:false
        }
    },
    methods:{
        async loadLazyData() {
            this.loading = true;
            const res = await getBpm(this.lazyParams);

            this.data = res.data;
            this.loading = false;
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
