<template>
    <layout title="Create- Cmd">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

  <div class="row">
  <div class="col">
    <DataTable :value="Create.data" :lazy="true" :paginator="true" :rows="Create.per_page" ref="dt"
      :totalRecords="Create.total" :loading="loading" @page="onPage($event)"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
      currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll"
      v-model:selection="selectedcreate" selectionMode="single" :metaKeySelection="metaKey" dataKey="id">
      <Column field="" header="No">
        <template #body="slotProps">
          {{ ((lazyParams.page - 1) * Create.per_page) + slotProps.index + 1 }}
        </template>
      </Column>
      <Column field="sn" header="Serial Number"></Column>
      <Column field="ip" header="IP "></Column>
      <template #empty>
        No records found
      </template>
    </DataTable>
  </div>

    <div class="card col-lg-7" style="padding-top: 30px; padding-bottom: 20px;" >
    <table>

    <tr>
        <td>
            <RadioButton type="radio" v-model="form.radiobutton" name="radio" value="control" class="form-check-input" id="control" style="margin-left: 3px;" />
            <label for="control">Control</label>
        </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateData" optionLabel="control"  class="custom-dropdown" style="width: 200px"  :disabled="form.radiobutton == 'control' ? false : true"/>
    </td>
    <td rowspan="6">
        <span class="p-float-label" style="width: 80%;">
        <Textarea id="value" v-model="command" :class="{ 'p-invalid': errorMessage }" rows="10" cols="50" aria-describedby="text-error">{{  form.command  }}</Textarea>
        <Button type="submit" label="Create" @click="simpan" style="margin-left: 310px;"></Button>
        </span>
    </td>
  </tr>
  <tr>
    <td>
        <RadioButton type="radio" v-model="form.radiobutton" name="radio" value="update" class="form-check-input" id="update" style="margin-left: 3px;"/>
        <label for="update">Update</label>
    </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateUpdate" optionLabel="control" class="custom-dropdown" style="width: 200px" :disabled="form.radiobutton == 'update' ? false : true"/>
    </td>
  </tr>
  <tr>
    <td>
        <RadioButton type="radio" v-model="form.radiobutton" name="radio"  value="delete" class="form-check-input" id="delete" style="margin-left: 3px;"/>
        <label for="delete">Delete</label>
    </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateDelete" optionLabel="control"  class="custom-dropdown" style="width: 200px"  :disabled="form.radiobutton == 'delete' ? false : true"/>
    </td>
  </tr>
  <tr>
    <td>
        <RadioButton type="radio" v-model="form.radiobutton" name="radio"  value="query" class="form-check-input" id="query" style="margin-left: 3px;"/>
        <label for="query">Query</label>
    </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateQuery" optionLabel="control" class="custom-dropdown" style="width: 200px"  :disabled="form.radiobutton == 'query' ? false : true"/>
    </td>
  </tr>
  <tr>
    <td>
        <RadioButton type="radio" v-model="form.radiobutton" name="radio"  value="clear" class="form-check-input" id="clear" style="margin-left: 3px;"/>
        <label for="clear">Clear</label>
    </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateClear" optionLabel="control" class="custom-dropdown" style="width: 200px"  :disabled="form.radiobutton == 'clear' ? false : true"/>
    </td>
  </tr>
  <tr>
    <td colspan="2">
        <RadioButton type="radio" v-model="form.radiobutton" name="radio"  value="user_defined" class="form-check-input" id="userdefined" style="margin-left: 3px;"/>
        <label for="userdefined">User - Defined</label>
    </td>
  </tr>

</table>
</div>
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
import RadioButton from 'primevue/radiobutton';
import InputText from 'primevue/inputtext';
import ColumnGroup from 'primevue/columngroup';
import {getcreate, upload} from '../../Api/createcmd.api';
import {ref} from "vue";

export default {
    name: "DeviceCmd",
    components: {
        ErrorsAndMessages,
        Layout,
        Column,
        InputText,
        DataTable,
        RadioButton,
        Toolbar,
        Button,
        ColumnGroup,
    },
    watch:{
        selectedcreate(val){
            this.form.sn = val.sn;
        },
        'form.control'(val){
            console.log("Data", val)
            if(this.form.radiobutton == 'control'){
               const cmd =  this.CreateData.find(x=>x.control == val.control);
               this.form.command = cmd.command;
            }
            else if (this.form.radiobutton == 'update'){
               const cmd =  this.CreateUpdate.find(x=>x.control == val.control);
               this.form.command = cmd.command;
            }
            else if (this.form.radiobutton == 'delete'){
               const cmd =  this.CreateDelete.find(x=>x.control == val.control);
               this.form.command = cmd.command;
            }
            else if (this.form.radiobutton == 'query'){
               const cmd =  this.CreateQuery.find(x=>x.control == val.control);
               this.form.command = cmd.command;
            }
            else if (this.form.radiobutton == 'clear'){
               const cmd =  this.CreateClear.find(x=>x.control == val.control);
               this.form.command = cmd.command;
            }
           console.log(this.CreateData, this.form.command);
        },
    },

    data() {
        return {
            loading:false,
            search:'',
            display:false,
            Create:[],
            createcmddata:[],
            CreateData:[],
            CreateUpdate:[],
            CreateDelete:[],
            CreateClear:[],
            CreateQuery:[],
            filters: {},
            command:[],
            modalTitle:null,
            submitted: false,
            selectedcreate:[],
            form:{
               sn: null,
               radiobutton:null,
               getDataDB:null,
               control:null,
               flag:null,


            },
            initform:{
               sn:null,
               radiobutton:null,
               control:null,
               flag:null,
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
            const res = await getcreate({page : this.lazyParams.page})

            this.Device = res.data.data;
            this.loading = false;

        },
        onPage(event) {
            this.lazyParams.page = event.page + 1;
            this.loadLazyData();

        },
        async simpan(){
        this.loading = true;
        const  data = await upload(this.form);
        console.log('Data berhasil dikirim');
        this.loading = false;
        this.$toast.add({
            severity: 'success',
            summary: 'Informasi!',
            detail: 'Berhasil Di simpan',
            life: 3000})
        }

    },

    mounted(){
        this.Create = this.$page.props.createcmd;
        this.CreateData = this.$page.props.createcmddata;
        this.CreateUpdate = this.$page.props.createupdate;
        this.CreateDelete= this.$page.props.createdelete;
        this.CreateQuery= this.$page.props.createquery;
        this.CreateClear= this.$page.props.createclear;

    }

};
const metaKey = ref(true);
const selectedcreate = ref();

</script>

<style>
    .custom-dropdown {
    margin-left: 20px;
}

    label[for="control"] {
        margin-left: 27px;
        margin-top: 3px;
    }

    label[for="update"] {
        margin-left: 27px;
        margin-top: 3px;
    }

    label[for="delete"] {
        margin-left: 27px;
        margin-top: 3px;
    }

    label[for="query"] {
        margin-left: 27px;
        margin-top: 3px;
    }

    label[for="clear"] {
        margin-left: 27px;
        margin-top: 3px;
    }

    label[for="update"] {
        margin-left: 27px;
        margin-top: 3px;
    }

    label[for="userdefined"] {
        margin-left: 27px;
        margin-top: 3px;
    }
</style>


