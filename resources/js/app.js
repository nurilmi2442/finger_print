require('./bootstrap');

import {createApp, h} from 'vue';
import 'primevue/resources/primevue.min.css'
import 'primevue/resources/themes/bootstrap4-light-blue/theme.css'
import 'primeicons/primeicons.css'
import ToastService from 'primevue/toastservice';
import { App, plugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress'
import PrimeVue from 'primevue/config';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup'; 
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import Dropdown from 'primevue/dropdown';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmationService from 'primevue/confirmationservice';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
import Loading from './Components/Loading';

const el = document.getElementById('app');

InertiaProgress.init();


    const app = createApp({
        render: () => h(App, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: name => require(`./Pages/${name}`).default
        })
    });

    app.config.globalProperties.$route = window.route;
    app.provide('$route', window.route);

    app.use(plugin).mount(el);

    app.use(PrimeVue);
    app.use(ToastService);
    app.use(ConfirmationService);

    app.component('DataTable', DataTable);
    app.component('Column', Column);
    app.component('ColumnGroup', ColumnGroup);
    app.component('Toolbar', Toolbar);
    app.component('Button', Button);
    app.component('InputText', InputText);
    app.component('Dialog',Dialog);
    app.component('Loading',Loading);
    app.component('Toast',Toast);
    app.component('Dropdown',Dropdown);
    app.component('ConfirmDialog',ConfirmDialog);
    app.component('Calendar',Calendar);
    app.component('AutoComplete', AutoComplete);
