import Vue from 'vue';
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue';
import DatePicker from 'vue2-datepicker';
import vSelect from 'vue-select';

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'vue2-datepicker/index.css';
import 'vue-select/dist/vue-select.css';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.component('date-picker', DatePicker);
Vue.component('v-select', vSelect);
