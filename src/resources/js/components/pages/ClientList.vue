<template>
    <table>
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
                    <td><v-btn :to="'contents'" :small="true">記事一覧</v-btn></td>
                    <td><v-btn :to="{name: 'client.edit', params: {id: data.id}}" :small="true">編集</v-btn></td>
                    <td><v-btn :small="true" color="error">削除</v-btn></td>
                </tr>
            </template>
            <template v-else>
                <tr><td colspan="3">{{ emptyMessage }}</td></tr>
            </template>
        </tbody>
    </table>
</template>

<style scoped>
</style>

<script lang="ts">
import Vue from 'vue';
import * as api from '../../config/api';
import {getCleints, Client} from '../../config/api'

type VMData = {
    clients: Client[],
    isLoading: boolean
}

export default Vue.extend({
    data(): VMData
    {
        return {
            clients: [],
            isLoading: false
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
        fetchData(): void
        {
            const id = parseInt(this.$route.params.id);
            getCleints()
            .then((res)=>{
                this.clients = res.data;
            })
            .catch(()=>{
                this.clients = [];
            })
        }
    }
})
</script>