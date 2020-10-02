<template>
</template>

<style lang="scss">
</style>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
    created()
    {
        console.log(this.$route.params)
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