<template>
    <div>
        <router-link :to="{name: 'content.register', params: {clientId: $route.params.clientId}}">新規作成</router-link>
    </div>
</template>

<style lang="scss">
</style>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
    created()
    {
        this.fetchData();
    },
    methods: {
        fetchData(): void
        {
            const id = parseInt(this.$route.params.clientId);
            console.log(id);
        },
    }
});
</script>