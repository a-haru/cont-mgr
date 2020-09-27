import VueRouter from 'vue-router';
import ClientList from '../components/pages/ClientList.vue';

const router = new VueRouter({
    routes: [
        {path: '/clients', component: ClientList}
    ]
});
