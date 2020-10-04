import Vue from 'vue';
import VueRouter, { RouteConfig } from 'vue-router';
Vue.use(VueRouter);

// import ClientList from '../components/Ecosystems/ClientList.vue';
import ClientList from '../components/Ecosystems/ClientList.vue';
import ClientCreate from '../components/Ecosystems/ClientCreate.vue';
import ClientEdit from '../components/Ecosystems/ClientEdit.vue';

import ContentList from '../components/Ecosystems/ContentList.vue';
import ContentEdit from '../components/Ecosystems/ContentEdit.vue';
import ContentCreate from '../components/Ecosystems/ContentCreate.vue';

export const routes: RouteConfig[]  = [
    {path: '/', redirect: {name: 'client.list'}},
    {name: 'client.list', path: '/clients', component: ClientList},
    {name: 'client.create', path: '/clients/create', component: ClientCreate},
    {name: 'client.edit', path: '/clients/:id/edit', component: ClientEdit},
    {name: 'content.list', path: '/contents/:clientId', component: ContentList},
    {name: 'content.create', path: '/contents/:clientId/create', component: ContentCreate},
    {name: 'content.edit', path: '/contents/:clientId/:contentId/edit', component: ContentEdit}
];

export default new VueRouter({
    routes
});
