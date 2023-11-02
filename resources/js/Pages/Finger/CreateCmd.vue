<template>
    <layout title="Create- Cmd">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

    <div class="row">
  <div class="col">
    <DataTable :value="Create.data" :lazy="true" :paginator="true" :rows="Create.per_page" ref="dt"
      :totalRecords="Create.total" :loading="loading" @page="onPage($event)"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
      currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" responsiveLayout="scroll">
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
            <input type="radio" class="form-check-input" id="update" value="F"  >
            <label for="update">Control</label>
        </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateData" optionLabel="control"  class="custom-dropdown"/>
    </td>
    <td rowspan="6">
        <span class="p-float-label" style="width: 100%;">
        <Textarea id="value" v-model="value" :class="{ 'p-invalid': errorMessage }" rows="10" cols="60"
        aria-describedby="text-error"></Textarea>
      <label for="value">Description</label>
        </span>
    </td>
  </tr>
  <tr>
    <td>
        <input type="radio" class="form-check-input" id="update" value="F">
        <label for="update">Update</label>
    </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateUpdate" optionLabel="control"   class="custom-dropdown"/>
    </td>
  </tr>
  <tr>
    <td>
        <input type="radio" class="form-check-input" id="update" value="F">
        <label for="update">Delete</label>
    </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateDelete" optionLabel="control"  class="custom-dropdown" />
    </td>
  </tr>
  <tr>
    <td>
        <input type="radio" class="form-check-input" id="update" value="F">
        <label for="update">Query</label>
    </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateQuery" optionLabel="control" class="custom-dropdown"/>
    </td>
  </tr>
  <tr>
    <td>
        <input type="radio" class="form-check-input" id="update" value="F">
        <label for="update">Clear</label>
    </td>
    <td>
        <Dropdown v-model="form.control" :options="CreateClear" optionLabel="control" class="custom-dropdown" />
    </td>
  </tr>
  <tr>
    <td colspan="2">
        <input type="radio" class="form-check-input" id="update" value="F">
        <label for="update">User - Defined</label>
    </td>
  </tr>

    <tr align="end">
    <td colspan="3">
        <small id="text-error" class="p-error">{{ errorMessage || '&nbsp;' }}</small>
        <Button type="submit" label="Create" @click="simpan"></Button>

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
import {getcreate} from '../../Api/createcmd.api';

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
            modalTitle:null,
            submitted: false,
            form:{
               control:null,
               flag:null,


            },
            initform:{
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
        // CreateData() {
        //     return this.createcmddata
        //     .filter(item => item.flag === 'control')
        //     .map(item => item.control);
        // },
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
</script>

<style>

    .custom-dropdown {
    margin-left: 20px;
}
    input.form-check-input {
        margin-left: 5px;
    }

    label[for="update"] {
        margin-left: 25px;
    }

</style>


