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
            <v-btn @click.prevent="updateContent" :disabled="!initialized">更新</v-btn>
        </v-form>
    </div>
</template>

<style lang="scss">
</style>
<script lang="ts">
import Vue from 'vue';
import * as dayjs from 'dayjs';

import {updateContent, fetchAutosaveContent, storeAutosaveContent, InitContentData, ContentData, fetchContent} from '../../config/api';

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
    },
    watch: {
        '$route': 'initialize'
    },
    methods: {
        initialize(): Promise<void>
        {
            this.initialized = false;
            const {clientId, contentId} = this.getParams();
            return fetchContent(clientId, contentId)
            .then((res) => {
                this.contentData = res.data;
                this.enableAutosave();
                return true;
            })
            .catch((err) => {
                this.$router.push({name: 'home'});
                return false;
            })
            .then((res) => {
                // finally
                if (res) {
                    return fetchAutosaveContent(clientId, contentId);
                } else {
                    return Promise.reject();
                }
            })
            .then((res) => {
                if (Object.keys(res.data).length === 0) {
                    return;
                }

                if (!confirm('自動保存されたデータが残っています。読み込みますか？')) {
                    return;
                }

                this.contentData = res.data;
            })
            .catch((err) => {})
            .then(() => {
                this.initialized = true;
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
                this.editId = setInterval(this.storeAutosaveContent, 1000 * 10);
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
        storeAutosaveContent(): Promise<boolean>
        {
            return new Promise(() => {
                if (!this.isEdited) {
                    // 編集されていなければ即時終了します
                    return true;
                }
                // 重複実行を防ぐため定期実行をキャンセルしておきます。
                this.disableAutosave();
                const { clientId, contentId } = this.getParams();

                let result = false;
                // 自動保存用APIに入力値を送付 
                return storeAutosaveContent(this.contentData, clientId, contentId)
                .then(()=>{
                    // 保存した時間を保持する
                    this.autosaveTime = dayjs().valueOf();
                    result = true;
                })
                .catch((err)=>{})
                .then(()=>{
                    this.enableAutosave();
                    return result;
                });
            });
        },

        /**
         * 保存処理
         * 
         * 保存後、オブジェクトで保持しているデータをリセット
         */
        updateContent(): Promise<boolean>
        {
            const {clientId, contentId} = this.getParams();
            let result = false;
            this.disableAutosave();
            return updateContent(clientId, contentId, this.contentData)
            .then(()=>{
                this.reset();
                result = true;
            })
            .catch((e)=>{
            })
            .then(()=>{
                this.enableAutosave()
                return result;
            });
        }
    }
});
</script>