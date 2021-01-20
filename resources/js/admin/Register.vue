<template>
    <div class="register">
        <!-- <h2>Register {{ $route.params.id }}</h2> -->
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    created() {
        this.$store.commit('SET_LAYOUT', 'no-layout')
    },
}
</script>

<style>

</style>