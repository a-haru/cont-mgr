<template>
    <div>
        <v-form>
            <v-text-field
            v-model="contentData.title"
            :counter="255"
            label="タイトル"
            @input="beginEditing"
            :disabled="!initialized"
            ></v-text-field>
            <v-text-field
            v-model="contentData.description"
            :counter="255"
            label="概要"
            @input="beginEditing"
            :disabled="!initialized"
            ></v-text-field>
            <v-textarea
            v-model="contentData.text"
            label="本文"
            @input="beginEditing"
            :disabled="!initialized"
            ></v-textarea>
            <v-textarea
            v-model="contentData.note"
            label="備考"
            @input="beginEditing"
            :disabled="!initialized"
            ></v-textarea>
            <p class="text-right">{{ autosaveTimeText }}</p>
            <v-btn @click.prevent="storeContent" :disabled="!initialized">更新</v-btn>
        </v-form>
    </div>
</template>

<style lang="scss">
</style>
<script lang="ts">
import Vue from 'vue';
import * as dayjs from 'dayjs';

import {storeContent, fetchAutosaveContent, automaticallySaveContent, InitContentData, ContentData, fetchContent} from '../../config/api';

type VMData = {
    initialized: boolean;
    autosaveTime: number|null;
    editId: number|null;
    isEdited: boolean;
    contentData: ContentData;
}

type URLParams = {
    clientId: number;
    contentId: number;
}

export default Vue.extend({
    data(): VMData
    {
        return {
            initialized: false,
            autosaveTime: null,
            editId: null,
            isEdited: false,
            contentData: {
                title: '',
                description: '',
                text: '',
                note: ''
            }
        }
    },
    computed: {
        /**
         * 自動保存を実行した時間を返す
         */
        autosaveTimeText(): string {
            if (!this.autosaveTime) {
                return '';
            }
            return dayjs(this.autosaveTime).format('自動保存: YYYY-MM-DD hh:mm:ss')
        }
    },
    created(): void
    {
        this.initialize();
    },
    beforeDestroy(): void
    {
        // 画面変更などでビューが破棄される時に自動保存処理を無効化しておきます。
        this.disableAutosave();
    }
    ,
    methods: {
        initialize(): Promise<void>
        {
            return new Promise((resolve, reject) => {
                const {clientId, contentId} = this.getParams();
                fetchContent(contentId)
                .then((res) => {
                    this.contentData = res.data
                    return fetchAutosaveContent(clientId, contentId);
                })
                .then((res) => {
                    if (Object.keys(res.data).length === 0) {
                        return resolve();
                    }

                    if (!confirm('自動保存されたデータが残っています。読み込みますか？')) {
                        return resolve();
                    }

                    console.log(res.data);
                    this.contentData = res.data;
                    resolve();
                })
                .catch(()=>{
                    reject();
                }).then(()=>{
                    // ビューの生成時に自動保存処理を有効化
                    this.initialized = true;
                    this.enableAutosave();
                });
            });
        },
        /**
         * 自動保存処理を有効化
         * 60秒に一回自動保存を試みます
         */
        enableAutosave(): void
        {
            if (this.editId === null) {
                this.initializeEditStatus();
                this.editId = setInterval(this.automaticallySaveContent, 1000 * 10);
            }
        },
        /**
         * 自動保存処理を無効化
         */
        disableAutosave(): void
        {
            if (this.editId !== null) {
                clearInterval(this.editId);
            }
            this.initializeEditStatus();
            this.editId = null;
        },
        /**
         * 編集中ステータスを初期化
         */
        initializeEditStatus(): void
        {
            this.isEdited = false;
        },
        /**
         * 編集中ステータスを更新
         */
        beginEditing(): void
        {
            this.isEdited = true;
        },
        /**
         * 保持しているデータを初期化
         */
        reset(): void
        {
            this.disableAutosave()
            this.autosaveTime = null;
            this.contentData = {
                ...InitContentData
            }
        },
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
        /**
         * 自動保存処理
         */
        automaticallySaveContent(): Promise<boolean>
        {
            return new Promise((resolve, reject) => {

                if (!this.isEdited) {
                    // 編集されていなければ即時終了します
                    return resolve(true);
                }

                // 重複実行を防ぐため定期実行をキャンセルしておきます。
                this.disableAutosave();
                const { clientId, contentId } = this.getParams();

                // 自動保存用APIに入力値を送付 
                automaticallySaveContent(this.contentData, clientId, contentId)
                .then(()=>{
                    // 保存した時間を保持する
                    this.autosaveTime = dayjs().valueOf();
                    resolve(true);
                })
                .catch(()=>{
                    reject(false);
                })
                .then(()=>{
                    this.enableAutosave();
                });
            });
        },

        /**
         * 保存処理
         * 
         * 保存後、オブジェクトで保持しているデータをリセット
         */
        storeContent(): Promise<boolean>
        {
            return new Promise((resolve, reject) => {
                const {clientId} = this.$route.params;
                this.disableAutosave();
                storeContent(parseInt(clientId), this.contentData)
                .then(()=>{
                    this.reset();
                    resolve(true);
                })
                .catch((e)=>{
                    reject(false);
                })
                .then(()=>{
                    this.enableAutosave();
                });
            })
        }
    }
});
</script>