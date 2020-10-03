import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

// import ClientList from '../components/Ecosystems/ClientList.vue';
import ClientList from '../components/Ecosystems/ClientList.vue';
import ClientRegister from '../components/Ecosystems/ClientRegister.vue';
import ClientEdit from '../components/Ecosystems/ClientEdit.vue';
import ContentList from '../components/Ecosystems/ContentList.vue';
import ContentRegister from '../components/Ecosystems/ContentRegister.vue';

export default new VueRouter({
    routes: [
        {name: 'client.list', path: '/clients', component: ClientList},
        {name: 'client.register', path: '/clients/register', component: ClientRegister},
        {name: 'client.edit', path: '/client/:id/edit', component: ClientEdit},
        {name: 'content.list', path: '/contents/:clientId', component: ContentList},
        {name: 'content.register', path: '/contents/:clientId/register', component: ContentRegister},
    ]
});
