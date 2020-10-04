<template>
    <div>
        <v-btn :to="{name: 'content.create', params: {clientId: $route.params.clientId}}" :text="true">新規作成</v-btn>
        <v-simple-table>
            <template v-slot:default>
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
                                <v-btn @click.prevent="deleteContent(data.client_id, data.id)" :small="true" :color="'error'">削除</v-btn>
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

<style lang="scss">
</style>

<script lang="ts">
import Vue from 'vue'

import { getContents, deleteContent, Content } from '../../config/api';

type VMData = {
    isLoading: boolean;
    contents: Content[];
}

type URLParams = {
    clientId: number;
    contentId: number;
}

export default Vue.extend({
    data(): VMData
    {
        return {
            isLoading: false,
            contents: []
        }
    },
    created(): void
    {
        this.fetchData();
    },
    watch: {
        '$route': 'fetchData'
    },
    computed: {
        emptyMessage(): string
        {
            if (this.isLoading) {
                return '読み込み中です';
            }
            if (this.contents.length === 0) {
                return '表示可能なデータが存在しません';
            }
            return '----';
        }
    },
    methods: {
        /**
         * URLのパラメータを取得する
         */
        getParams(): URLParams
        {
            const rawClientId = this.$route.params.clientId;
            const rawContentId = this.$route.params.contentId;

            return {
                clientId: Number(rawClientId),
                contentId: rawContentId ? Number(rawContentId) : 0
            }
        },
        fetchData(): Promise<void>
        {
            const id = parseInt(this.$route.params.clientId);
            this.isLoading = true;
            return getContents(id)
            .then((res)=>{
                this.contents = res.data;
            })
            .catch(() => {})
            .then(() => {
                this.isLoading = false;
            });
        },
        removeContent(id: number): boolean
        {
            const index = this.contents.findIndex((data)=>{
                return data.id === id;
            });
            if (index >= 0) {
                this.contents.splice(index, 1);
                return true;
            }
            return false;
        },
        deleteContent(clientId: number, contentId: number): Promise<boolean>
        {
            return deleteContent(clientId, contentId)
            .then(()=>{
                this.removeContent(contentId);
                return true;
            })
            .catch(()=>{
                return false;
            });
        }
    }
});
</script>