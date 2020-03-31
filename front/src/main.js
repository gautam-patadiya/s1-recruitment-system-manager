import Vue from 'vue';
import './ant.js';
import App from './App.vue';
import router from './router';
import Private from "./layouts/Private";
import Public from "./layouts/Public";
import {handleSyncRequestLoader} from "./util/utils";
import VueProgressBar from 'vue-progressbar';
import Front from "./layouts/Front";
import store from './store/index';
import axios from 'axios'

Vue.component('private-layout', Private);
Vue.component('public-layout', Public);
Vue.component('front-layout', Front);

Vue.use(VueProgressBar, {
    color: 'rgb(143, 250, 255)',
    failedColor: 'red',
    autoFinish: false
});

Vue.config.productionTip = false;

axios
    .get('/settings.json')
    .then((response) => {
        const {data} = response;
        const storeInstance = store(data);

        new Vue({
            store: storeInstance,
            render: h => h(App),
            router,
            created() {
                handleSyncRequestLoader(storeInstance, data.app_url);
            }
        }).$mount('#app');
    })
    .catch((errors) => {
        alert('Unable to load Setting file :( ');
        thisFunctionDoesNotExistAndWasCreatedWithTheOnlyPurposeOfStopJavascriptExecutionOfAllTypesIncludingCatchAndAnyArbitraryWeirdScenario();
    });
