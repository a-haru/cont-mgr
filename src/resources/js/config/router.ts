import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

// import ClientList from '../components/pages/ClientList.vue';
import ClientList from '../components/pages/ClientList';
import ClientEdit from '../components/pages/ClientEdit';

export default new VueRouter({
    routes: [
        {name: 'client.list', path: '/clients', component: ClientList},
        {name: 'client.edit', path: '/client/:id/edit', component: ClientEdit}
    ]
});
