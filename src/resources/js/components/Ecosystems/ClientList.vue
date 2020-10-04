<template>
    <div>
        <v-btn :to="{name: 'client.create'}" :text="true">新規作成</v-btn>
        <v-simple-table>
            <template v-slot:default>
                <thead>
                    <tr>
                        <th>顧客名</th>
                        <th>サイトURL</th>
                        <th>アクション</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="clients.length !== 0">
                        <tr v-for="(data, idx) in clients" :key="idx">
                            <td>{{data.name}}</td>
                            <td>{{data.url}}</td>
                            <td>
                                <v-btn :to="{name: 'content.list', params: {clientId: data.id}}" :small="true">記事一覧</v-btn>
                                <v-btn :to="{name: 'client.edit', params: {id: data.id}}" :small="true">編集</v-btn>
                                <v-btn :small="true" color="error" @click="deleteClient(data.id)">削除</v-btn>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr><td colspan="3">{{ emptyMessage }}</td></tr>
                    </template>
                </tbody>
            </template>
        </v-simple-table>
    </div>
</template>

<style scoped>
</style>

<script lang="ts">
import Vue from 'vue';
import {fetchClients, Client, deleteClient} from '../../config/api'

type VMData = {
    clients: Client[];
    isLoading: boolean;
    isDeleting: boolean;
}

export default Vue.extend({
    data(): VMData
    {
        return {
            clients: [],
            isLoading: false,
            isDeleting: false
        }
    },
    computed: {
        emptyMessage() {
            if (this.isLoading) {
                return '読み込み中です';
            }
            if (this.clients.length === 0) {
                return '表示可能なデータが存在しません';
            }
            return '----';
        }
    },
    created()
    {
        this.fetchData()
    },
    watch: {
        '$route': 'fetchData'
    },
    methods: {
        removeClient(id: number): boolean
        {
            const index = this.clients.findIndex((data)=>{
                return data.id === id;
            });
            if (index >= 0) {
                this.clients.splice(index, 1);
                return true;
            }
            return false;
        },
        fetchData(): Promise<void>
        {
            this.isLoading = true;
            const id = parseInt(this.$route.params.id);
            return fetchClients()
            .then((res)=>{
                this.clients = res.data;
            })
            .catch(()=>{
                this.clients = [];
            })
            .then(()=>{
                this.isLoading = false;
            })
        },

        deleteClient(id: number): void
        {
            if (this.isDeleting) {
                return;
            }
            this.isDeleting = true;
            deleteClient(id)
            .then(()=>{
                this.isDeleting = false;
                this.removeClient(id);
            })
            .catch(()=>{
                this.isDeleting = false;
            })
        }
    }
})
</script>