<template>
  <div>
    <v-app id="inspire">
      <!-- <v-app-bar
        class="deep-purple"
        elevation
        fixed 
        dark
      >
        <v-toolbar-title class="font-weight-medium display-1">EShop</v-toolbar-title>
        
      </v-app-bar> -->
      <router-view />
    </v-app>
  </div>
</template>
<script>
import axios from 'axios';
export default {
  data() {
    return {
    }
  },
  computed: {
  },
  methods: {
    
  }
}
</script>