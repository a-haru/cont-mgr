import './bootstrap';
import Vue from 'vue';

import router from './config/router.ts';
import vuetify from './config/vuetify.ts';

import RootComponent from './components/Root.vue';

const app = new Vue({
    router,
    vuetify,
    components: {
        root: RootComponent
    }
}).$mount('#app');
