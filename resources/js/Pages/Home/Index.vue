<template>
<layout>
	<div>
        <div style="background-color:white;height: 200px;width: 100%;display: flex;justify-content: center;align-items: center;flex-direction: column;margin-top: 10rem">
            <label for="">Selamat Datang</label>
            <span class="brand-text font-weight-light text" style="font-size: 75px;"><b style="color: #00336a;">IMS</b> ATTENDANCE</span>
            <small><b>Made By Tim Divisi Teknologi Informasi</b> </small>
        </div>
    </div>
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
        // this.data = this.$page.props.data;
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
