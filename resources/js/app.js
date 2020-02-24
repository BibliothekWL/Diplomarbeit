import './bootstrap';
import Vue from 'vue';

import 'bootstrap/dist/css/bootstrap.css';

import {library} from '@fortawesome/fontawesome-svg-core';
import {faPlus} from '@fortawesome/free-solid-svg-icons';
import {faTrash} from '@fortawesome/free-solid-svg-icons';
import {faPen} from '@fortawesome/free-solid-svg-icons';
import {faSearch} from '@fortawesome/free-solid-svg-icons';
import {faLevelUpAlt} from '@fortawesome/free-solid-svg-icons';
import {faCartPlus} from '@fortawesome/free-solid-svg-icons';
import {faAngleLeft} from '@fortawesome/free-solid-svg-icons';
import {faAngleDoubleLeft} from '@fortawesome/free-solid-svg-icons';
import {faAngleRight} from '@fortawesome/free-solid-svg-icons';
import {faAngleDoubleRight} from '@fortawesome/free-solid-svg-icons';
import {faChevronLeft} from '@fortawesome/free-solid-svg-icons';
import {faFilter} from '@fortawesome/free-solid-svg-icons';
import {faTimes} from '@fortawesome/free-solid-svg-icons';
import {faShoppingCart} from '@fortawesome/free-solid-svg-icons';
import {faArrowRight} from '@fortawesome/free-solid-svg-icons';
import {faArrowLeft} from '@fortawesome/free-solid-svg-icons';
import {faSortAlphaDown} from '@fortawesome/free-solid-svg-icons';
import {faSortAlphaDownAlt} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

library.add(faPlus, faSortAlphaDown, faSortAlphaDownAlt, faArrowRight, faArrowLeft, faShoppingCart, faTimes, faFilter, faTrash, faPen, faSearch, faLevelUpAlt, faCartPlus, faAngleLeft, faAngleDoubleLeft, faAngleRight, faAngleDoubleRight, faChevronLeft);

Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.config.productionTip = false;

import Vuesax from 'vuesax'

import 'vuesax/dist/vuesax.css'
Vue.use(Vuesax);

import 'material-icons/iconfont/material-icons.css';

import store from './store';

import Routes from '@/js/routes';
import App from '@/js/views/App';
import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);

import vueDebounce from 'vue-debounce'

Vue.use(vueDebounce);

Vue.use(vueDebounce, {
    lock: true
});

Vue.use(vueDebounce, {
    listenTo: 'input'
});

Vue.use(vueDebounce, {
    listenTo: ['input', 'keyup']
});

Vue.use(vueDebounce, {
    defaultTime: '400ms'
});

const app = new Vue({
    el: '#app',
    store,
    router: Routes,
    render: h => h(App)
});

export default app;