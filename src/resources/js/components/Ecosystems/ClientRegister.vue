<template>
    <v-form>
        <v-text-field
        v-model="client.name"
        :counter="255"
        label="顧客名"
        required
        ></v-text-field>
        <v-text-field
        v-model="client.url"
        :counter="255"
        label="サイトURL"
        ></v-text-field>
        <v-text-field
        v-model="client.contract_activate_at"
        label="契約開始日"
        ></v-text-field>
        <v-text-field
        v-model="client.contract_deactivate_at"
        label="契約終了日"
        ></v-text-field>
        <v-btn @click="store">登録</v-btn>
    </v-form>
</template>

<style lang="sass">
</style>

<script lang="ts">
import Vue from 'vue'
import {storeClient, StoreClient} from '../../config/api';

type VMData = {
    client: StoreClient
}

const clientDataInit: StoreClient = {
    name: '',
    url: '',
    contract_activate_at: '',
    contract_deactivate_at: ''
}

export default Vue.extend({
    data(): VMData
    {
        return {
            client: {
                ...clientDataInit
            }
        }
    },
    methods: {
        store(): Promise<boolean>
        {
            return storeClient(this.client)
            .then(()=>{
                this.reset();
                return true;
            })
            .catch(()=>{
                return false;
            })
        },
        reset(): void
        {
            this.client = {...clientDataInit}
        }
    }
})
</script>
