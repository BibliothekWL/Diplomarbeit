import './bootstrap';
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';

Vue.use(BootstrapVue);

import 'bootstrap/dist/css/bootstrap.css';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faPlus } from '@fortawesome/free-solid-svg-icons';
import { faTrash } from '@fortawesome/free-solid-svg-icons';
import { faPen } from '@fortawesome/free-solid-svg-icons';
import { faSearch } from '@fortawesome/free-solid-svg-icons';
import { faLevelUpAlt } from '@fortawesome/free-solid-svg-icons';
import { faCartPlus } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faPlus, faTrash, faPen, faSearch, faLevelUpAlt, faCartPlus);

Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.config.productionTip = false;

import store from './store';

import Routes from '@/js/routes';
import App from '@/js/views/App';

const app = new Vue({
    el: '#app',
    store,
    router: Routes,
    render: h => h(App)
});

export default app;