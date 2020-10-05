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
        <v-btn @click="updateCient" :disabled="isUpdating">更新</v-btn>
    </v-form>
</template>

<style lang="sass">
</style>

<script lang="ts">
import Vue from 'vue'
import {fetchClient, Client, ClientInit, updateClient} from '../../config/api';

type VMData = {
    client: Client,
    isLoading: boolean,
    isUpdating: boolean
}
const clientInit: Client = {...ClientInit};

export default Vue.extend({
    data(): VMData
    {
        return {
            isLoading: false,
            isUpdating: false,
            client: clientInit
        }
    },
    created () {
        this.fetchData()
    },
    watch: {
        '$route': 'fetchData'
    },
    methods: {
        fetchData(): void
        {
            const id = parseInt(this.$route.params.id);
            fetchClient(id)
            .then((res)=>{
                this.client = res.data;
            })
            .catch(()=>{
                this.client = {...ClientInit};
                this.$router.push({name: 'home'});
            })
        },
        updateCient(): Promise<boolean>
        {
            const id = parseInt(this.$route.params.id);
            let result = false;
            this.isUpdating = true;
            return updateClient(id, this.client)
            .then((res) => {
                result = res.data;
            })
            .catch(() => {
            })
            .then(() => {
                this.isUpdating = false;
                return result;
            });
        }
    }
})
</script>
