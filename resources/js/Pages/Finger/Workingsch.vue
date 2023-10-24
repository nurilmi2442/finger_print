<template>
    <layout title="Schedule - Master">
    <ConfirmDialog></ConfirmDialog>
    <Toast position="top-center" />

    <div class="row">
        <div class="col">
            <label for="departemen" class="control-label"
                style="display: block; margin-top: 1rem;">Periode :</label>
            <Dropdown v-model="form.nama_departemen" :options="dataDepartemen"  @change="filterData" optionLabel="nama_departemen"
                optionValue="id" placeholder="Select a Departemen" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>
        <div class="col">
            <label for="tanggal" class="control-label"
                style="display: block; margin-top: 1rem;">Start date :</label>
            <InputText type="date" v-model="selectedProject" @input="filterData"/>
        </div>
        <div class="col">
            <label for="tanggal" class="control-label"
                style="display: block; margin-top: 1rem;">End date:</label>
            <InputText type="date" v-model="selectedProject" @input="filterData"/>
        </div>
        <div class="col">
            <label for="datashift" class="control-label"
                style="display: block; margin-top: 1rem;">Unit :</label>
            <Dropdown v-model="form.nama_shift" :options="dataShift" @change="filterData"
                optionLabel="nama" optionValue="id" placeholder="Select a Shift" style="width: 100%!important" :filter="true"  filterPlaceholder="Cari..." />
        </div>


        <div class="p-mb-6">
                <Button label="Process" style="display: block; margin-top: 3.2rem;" class="p-button-sm p-button-sm" @click="process"></Button>
        </div>
    </div>

        <div class="card" style="margin-top: 20px;">
            <DataTable>
                <Column field="" header="No">
                    <template #body="slotProps">
                        {{ ((lazyParams.page - 1) * dataSchedulemaster.per_page) + slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="nama" header="Nama"></Column>
                <Column field="nama_group" header="Shift"></Column>
                <Column field="selected_date" header="Tanggal"></Column>
                <Column field="">
                    <template #body="slotProps">
                        <!-- <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="onEdit(slotProps.data)" style="margin-right: 10px;"></Button> -->
                        <Button @click="onDelete(slotProps.data)" icon="pi pi-trash" class="p-button-rounded p-button-warning" ></Button>
                    </template>
                </Column>
                <template #empty>
                    No records found
                </template>
            </DataTable>

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


export default {
    name: "Working",
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

};
</script>
