<template>
  <v-app>
      <div class="pnf__bg mt-16"></div>
      <h2 class="text-center mt-6">Page Not Found</h2>
  </v-app>
</template>

<script>
export default {
    created() {
        this.$store.commit('SET_LAYOUT', 'no-layout')
    }
}
</script>

<style>

</style>