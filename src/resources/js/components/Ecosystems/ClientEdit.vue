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
        v-model="client.contractActivateAt"
        label="契約開始日"
        ></v-text-field>
        <v-text-field
        v-model="client.contractDeactivateAt"
        label="契約終了日"
        ></v-text-field>
    </v-form>
</template>

<style lang="sass">
</style>

<script lang="ts">
import Vue from 'vue'
import {getCleint, Client, CleintInit} from '../../config/api'

type VMData = {
    client: Client,
    isLoading: boolean
}
const clientInit: Client = {...CleintInit};

export default Vue.extend({
    data(): VMData
    {
        return {
            isLoading: false,
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
            getCleint(id)
            .then((res)=>{
                this.client = res.data;
            })
            .catch(()=>{
                this.client = {...CleintInit};
            })
        }
    }
})
</script>
