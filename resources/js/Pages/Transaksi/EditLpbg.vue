<template>
<layout title="LPBG">
	<Loading :loading="loadingData"></Loading>
	<ConfirmDialog></ConfirmDialog>
	 <div>
        <div class="card">
            <Toolbar class="p-mb-4">
                 <template #right>
                    <Button label="Simpan" icon="pi pi-save" class="p-button-success p-mr-2"  @click="simpan"/>
                </template>
            </Toolbar>
           	<form class="p-4">
           		<div class="row">
	           		<div class="col-md-6">
	           			<label for="">Nomor LPBG</label>
	           			<InputText class="input" type="text" placeholder="Nomor LPBG" v-model="form.no_lpbg"/>
	           		</div>
	           		<div class="col-md-6">
	           			<label for="">Tanggal</label>
	           			<Calendar placeholder="Tanggal" class="input" id="basic" v-model="form.tanggal" autocomplete="off" :showIcon="true"/>
	           		</div>
	           		<div class="col-md-6">
	           			<label for="">Kode Proyek</label>
	           			<Dropdown v-model="selectedProyek"  :options="proyek" optionLabel="name" placeholder="Pilih Proyek" />
	           		</div>
           		</div>
           		<div class="row mt-4">
           			<div class="col">
           				<div class="card">
           					<Toolbar class="p-mb-4">
           						     <template #left>
           						     	<AutoComplete field="name" v-model="selectedMaterial" class="mr-3" :suggestions="filteredMaterial" @complete="searchMaterial($event)" placeholder="Pilih Material" style="width: 300px!important;" />
           						     	<InputText class="input" type="text" placeholder="Qty" v-model="material.qty" style="width: 100px!important;"/>
           						     </template>
					                 <template #right>
					                    <Button label="Tambah" icon="pi pi-plus" class="p-button-success p-mr-2"  @click="addMaterial"/>
					                </template>
					        </Toolbar>

					         <DataTable :value="dataMaterialLpbg.data" :lazy="true" :paginator="true" :rows="dataMaterialLpbg.per_page" ref="dt"
				            :totalRecords="dataMaterialLpbg.total" :loading="loading" @page="onPage($event)"
				                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
				                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
				                  <template #empty>
				                        No records found
				                    </template>

				                <Column field="no" header="No" :sortable="false" style="min-width:2rem">
				                    <template #body="slotProps">
				                         {{ ((lazyParams.page * dataMaterialLpbg.per_page) + slotProps.index ) + 1 }}
				                    </template>
				                </Column>
				                <Column field="kode_material" header="Kode Material" :sortable="false" style="min-width:16rem">
				                </Column>
				                <Column field="deskripsi" header="Deskripsi Material" :sortable="false" style="min-width:16rem"></Column>
				                <Column field="qty" header="qty" :sortable="false" style="min-width:2rem"></Column>
                                <Column field="satuan" header="Satuan" :sortable="false" style="min-width:2rem"></Column>
				                <Column :exportable="false" style="min-width:16rem">
				                    <template #body="slotProps">
				                        <Button @click="onDelete(slotProps.data)" icon="pi pi-trash" class="p-button-rounded p-button-warning" />
				                    </template>
				                </Column>
				            </DataTable>
           				</div>
           			</div>
           		</div>
           	</form>
        </div>

      
    </div>
</layout>
       
</template>

<script>
import Layout from "../../Partials/Layout";
import { getFormattedDate } from '../../helpers';
import ErrorsAndMessages from "../../Partials/ErrorsAndMessages";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {computed, inject} from "vue";
import { simpanLpbg, ambilMaterial, simpanMaterialLpbg, getMaterialLpbg, hapusMaterialLpbg } from '../../Api/transaksi.api';

export default {
    name: "Posts",
    components: {
        ErrorsAndMessages,
        Layout,
    },
   data() {
        return {
        dataMaterialLpbg:[],
        selectedProyek:[],
        proyek:[],
        form:{
            id:null,
            no_lpbg:null,
            tanggal:null,
            kode_proyek:null
            
        },
        material:{
        	material_id:null,
        	qty:null,
        	lpbghdr_id:null,
        },
        initmaterial:{
        	material_id:null,
        	qty:null,
        	lpbghdr_id:null,
        },
        selectedMaterial:[],
        filteredMaterial: null,
        initform:{
            id:null,
            no_lpbg:null,
            tanggal:null,
            kode_proyek:null
        },
        lazyParams: {
            page:0
        },
        loading:false,
        loadingData:false,
        display:false
        }
    },
    methods:{
        async searchMaterial(event){
        	const response = await ambilMaterial({search:event.query});
        	this.filteredMaterial = response.data.data;
        },
        onPage(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        onFilter() {
            this.lazyParams.filters = this.filters;
            this.loadLazyData();
        },
        onDelete(data){
        	this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: async () => {
                    await hapusMaterialLpbg({id:data.id});
                    await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di hapus', life: 3000});
                    await this.getMaterialLpbg();
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        async getMaterialLpbg(){
        	this.loading = true;
        	const response = await getMaterialLpbg({lpbghdr_id : this.form.id, page:this.lazyParams.page + 1});
        	this.loading = false;
        	this.dataMaterialLpbg = response.data.data
        },
        async addMaterial(){
        	this.loadingData = true;
        	const response = await simpanMaterialLpbg(this.material);
        	this.loadingData = false;
        	this.getMaterialLpbg();
        	Object.assign(this.material,this.initmaterial);
        },
        edit(item){
           this.$inertia.visit(`/proyek/edit/${item.id}`);
        },
        async simpan(){
            try{
            	this.loadingData = true;
            	if(this.form.tanggal){
	            	this.form.tanggal = await getFormattedDate(this.form.tanggal);
	            }

                const response = await simpanLpbg(this.form);
                this.loadingData = false;
                location.replace('/transaksi/lpbg');
            }
            catch(error){
                this.$toast.add({severity:'error', summary: 'Informasi!', detail:'Gagal Di simpan', life: 3000});
            }
           
        }
    },
    watch:{
        selectedProyek(val){
            this.form.kode_proyek = val.kode_proyek;
        },
        selectedMaterial(val){
        	this.material.lpbghdr_id = this.$page.props.data.data.id;
        	this.material.material_id = val.id;
        	
        }
    },
    setup() {
            
         // const data = computed(() => usePage().props.value.data);
    

        // return {
        //     data
        // }
    },
    async mounted(){
	   var dateObject = new Date(this.$page.props.data.data.tanggal_); 
       this.form = {
       		id : this.$page.props.data.data.id,
       		tanggal:dateObject,
       		no_lpbg:this.$page.props.data.data.no_lpbg
       }
       this.proyek = this.$page.props.proyek.data;
       this.selectedProyek = await this.proyek.find(x=>x.kode_proyek == this.$page.props.data.data.kode_proyek);
       await this.getMaterialLpbg();
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
