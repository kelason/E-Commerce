<template>
  <v-app id="inspire">
    <v-row
        no-gutters
    >
      <v-col
          cols="12"
          sm="12"
          md="4"
          lg="4"
          class="hidden-xs-only"
      >
        <v-card
          class="mx-auto"
          outlined
        >
          <v-list-item three-line>
            <v-list-item-content>
              <v-list-item-title class="headline mb-1">
                76%
              </v-list-item-title>
              <v-list-item-subtitle>
                Active Products
              </v-list-item-subtitle>
              <v-list-item-title>
                <v-progress-linear value="76"></v-progress-linear>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-card>
      </v-col>
    </v-row>
  </v-app>
</template>

<script>
export default {
}
</script>

<style>

</style>