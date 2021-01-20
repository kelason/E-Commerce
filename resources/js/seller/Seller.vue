<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    created() {
        this.$store.commit('SET_LAYOUT', 'seller-layout')
    }
}
</script>

<style>

</style>