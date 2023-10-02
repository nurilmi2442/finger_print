<template>
<layout title="STTP">
	<Loading :loading="loadingData"></Loading>
	<ConfirmDialog></ConfirmDialog>
	   <Toast position="top-center" />
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
	           			<label for="">Nomor STTP</label>
	           			<InputText class="input" type="text" placeholder="Nomor STTP" v-model="form.no_sttp" readonly/>
	           		</div>
	           		<div class="col-md-6">
	           			<label for="">Tanggal</label>
	           			<Calendar placeholder="Tanggal" class="input" id="basic" v-model="form.tanggal" autocomplete="off" :showIcon="true"/>
	           		</div>
	           		<div class="col-md-6">
	           			<label for="">Gedung</label>
	           			<Dropdown id="gedung" :options="dataGedung" optionLabel="gedung" placeholder="Select an item"  v-model="selectedGedung" style="width: 100%!important" :filter="true" filterPlaceholder="Cari..."/>
	           		</div>
	           		<div class="col-md-6">
	           			<label for="">Lokasi</label>
	           			<Dropdown id="lokasi" :options="dataLokasi" optionLabel="lokasi" placeholder="Select an item"  v-model="selectedLokasi" style="width: 100%!important" :filter="true" filterPlaceholder="Cari..."/>
	           		</div>
	           		<div class="col-md-6">
	           			<label for="">WBS</label>
	           			<Dropdown id="project" :options="dataProject" optionLabel="name" placeholder="Select an item"  v-model="selectedProject" style="width: 100%!important" :filter="true" filterPlaceholder="Cari..."/>
	           		</div>
	           		<div class="col-md-6">
	           			<label for="">Nomor Kereta</label>
	           		 	 <InputText type="text" style="width: 100%!important" v-model="form.nomor_kereta"/>
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
                                        <Button label="Cetak" icon="pi pi-print" class="p-button-secondary p-mr-2 mr-2"  @click="print"/>
					                    <Button label="Tambah" icon="pi pi-plus" class="p-button-success p-mr-2"  @click="addMaterial"/>
					                </template>
					        </Toolbar>

					         <DataTable :value="dataMaterialSttp.data" :lazy="true" :paginator="true" :rows="dataMaterialSttp.per_page" ref="dt"
				            :totalRecords="dataMaterialSttp.total" :loading="loading" @page="onPage($event)"
				                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
				                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
				                  <template #empty>
				                        No records found
				                    </template>

				                <Column field="no" header="No" :sortable="false" style="min-width:2rem">
				                    <template #body="slotProps">
				                         {{ ((lazyParams.page * dataMaterialSttp.per_page) + slotProps.index ) + 1 }}
				                    </template>
				                </Column>
                                <Column field="no_lpbg" header="No LPBG" :sortable="false" style="min-width:16rem">
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
import { simpanSttp, ambilMaterial, simpanMaterialSttp, getMaterialSttp, hapusMaterialSttp, getMaterialLpbg } from '../../Api/transaksi.api';

export default {
    name: "Posts",
    components: {
        ErrorsAndMessages,
        Layout,
    },
   data() {
        return {
        dataMaterialSttp:[],
        selectedProject:[],
        selectedGedung:[],
        selectedLokasi:[],
        proyek:[],
	    form:{
            id:null,
            gedung_id:null,
            lokasi_id:null,
            no_sttp:null,
            tanggal:null,
            kode_proyek:null,
            nomor_kereta:null
	    },
        material:{
        	lpbgdtl_id:null,
        	qty:null,
        	sttphdr_id:null,
        },
        initmaterial:{
        	lpbgdtl_id:null,
        	qty:null,
        	sttphdr_id:null,
        },
        selectedMaterial:[],
        filteredMaterial: null,
        dataProject:[],
        dataLokasi:[],
        dataGedung:[],
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
        	const response = await getMaterialLpbg({search:event.query, kode_proyek:this.form.kode_proyek,is_search:true});
        	this.filteredMaterial = response.data.data;
        },
        onPage(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        print(){
            const params = {
                sttphdr_id : btoa(this.form.id),
            }
            var query = new URLSearchParams(params).toString();
            window.open('/cetak-pdf/sttp?' + query, '_blank');
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
                    await hapusMaterialSttp({id:data.id});
                    await this.$toast.add({severity:'success', summary: 'Informasi!', detail:'Berhasil Di hapus', life: 3000});
                    await this.getMaterialSttp();
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        async getMaterialSttp(){
        	this.loading = true;
        	const response = await getMaterialSttp({sttphdr_id : this.form.id, page:this.lazyParams.page + 1});
        	this.loading = false;
        	this.dataMaterialSttp = response.data.data
        },
        async addMaterial(){
        	if(this.material.lpbgdtl_id && this.material.qty){
        		this.loadingData = true;
	        	const response = await simpanMaterialSttp(this.material);
	        	this.loadingData = false;
	        	this.getMaterialSttp();
	        	Object.assign(this.material,this.initmaterial);
                this.selectedMaterial = [];
        	}
        	else{
        		 this.$toast.add({severity:'info', summary: 'Informasi!', detail:'Material tidak boleh kosong', life: 3000});
        	}
        	
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

                const response = await simpanSttp(this.form);
                this.loadingData = false;
                location.replace('/transaksi/sttp');
            }
            catch(error){
                this.$toast.add({severity:'error', summary: 'Informasi!', detail:'Gagal Di simpan', life: 3000});
            }
           
        }
    },
    watch:{
        selectedProject(val){
            this.form.kode_proyek = val.kode_proyek;
        },
        selectedMaterial(val){
        	this.material.sttphdr_id = this.$page.props.data.data.id;
        	this.material.lpbgdtl_id = val.id;
        	
        },
         selectedGedung:function(val){
                this.form.gedung_id = val.id;
        },
        selectedLokasi:function(val){
                this.form.lokasi_id = val.id;
        },
    },
    setup() {
            
         // const data = computed(() => usePage().props.value.data);
    

        // return {
        //     data
        // }
    },
    async mounted(){
	   var dateObject = new Date(this.$page.props.data.data.tanggal_); 
	   this.dataProject = this.$page.props.proyek;
       this.dataGedung = this.$page.props.gedung;
       this.dataLokasi = this.$page.props.lokasi;
       this.form = {
       		id : this.$page.props.data.data.id,
       		tanggal:dateObject,
       		nomor_kereta:this.$page.props.data.data.nomor_kereta,
       		no_sttp:this.$page.props.data.data.no_sttp,
       		gedung_id:this.$page.props.data.data.gedung_id,
       		lokasi_id: this.$page.props.data.data.lokasi_id,
       		kode_proyek:this.$page.props.data.data.kode_proyek
       }

       this.selectedGedung = await this.dataGedung.find(x=>x.id == this.$page.props.data.data.gedung_id);
       this.selectedLokasi = await this.dataLokasi.find(x=>x.id == this.$page.props.data.data.lokasi_id);
       this.selectedProject = await this.dataProject.find(x=>x.kode_proyek == this.$page.props.data.data.kode_proyek);
       await this.getMaterialSttp();
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
