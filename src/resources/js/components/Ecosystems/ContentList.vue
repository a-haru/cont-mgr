<template>
    <div>
        <router-link :to="{name: 'content.register', params: {clientId: $route.params.clientId}}">新規作成</router-link>
        <table>
            <thead>
                <tr>
                    <th>タイトル</th>
                    <th>概要</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                <template v-if="contents.length > 0">
                    <tr v-for="(data, idx) in contents" :key="idx">
                        <td>{{data.title}}</td>
                        <td>{{data.description}}</td>
                        <td>
                            <v-btn :to="{name: 'content.edit', params: {clientId: data.client_id, contentId: data.id}}" :small="true">編集</v-btn>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr><td colspan="3">{{ emptyMessage }}</td></tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<style lang="scss">
</style>

<script lang="ts">
import Vue from 'vue'

import { getContents, Content } from '../../config/api';

type VMData = {
    contents: Content[]
}

export default Vue.extend({
    data(): VMData
    {
        return {
            contents: []
        }
    },
    created()
    {
        this.fetchData();
    },
    computed: {
        emptyMessage(): string
        {
            return 'からです'
        }
    },
    methods: {
        fetchData(): Promise<void>
        {
            const id = parseInt(this.$route.params.clientId);
            return getContents(id)
            .then((res)=>{
                this.contents = res.data;
            })
            .catch(()=>{

            });
        },
    }
});
</script>