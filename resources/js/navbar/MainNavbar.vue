<template>
  <div>
    <v-app id="inspire">
      <!-- <v-progress-linear
        indeterminate
        fixed
        color="white"
        style="z-index:6"
      ></v-progress-linear> -->
      <v-app-bar
        class="deep-purple"
        elevate-on-scroll 
        fixed 
        dark
      >
        <router-link
          class="text-decoration-none"
          to="/"
        >
          <v-toolbar-title class="font-weight-medium white--text display-1">EShop</v-toolbar-title>
        </router-link>
        <v-spacer></v-spacer>
        <!-- <v-text-field
          class="mt-3"
          placeholder="Search Item"
        >
          <v-icon
            slot="append"
            color="white"
          >
            mdi-magnify
          </v-icon>
        </v-text-field> -->
        <v-spacer></v-spacer>
        <span class="hidden-md-and-up">
          <v-btn icon @click.stop="drawer = !drawer">
            <v-icon>mdi-menu</v-icon>
          </v-btn>
        </span>
        <v-btn v-if="authenticated" text class="hidden-sm-and-down" to="/cart">
          <v-badge
            :content="count"
            :value="count"
          >
            <v-icon>mdi-cart-variant</v-icon>
            Cart
          </v-badge>
        </v-btn>

        <v-btn v-if="!authenticated" text class="hidden-sm-and-down" to="/signIn">
          <v-icon>mdi-login</v-icon>
          Sign In
        </v-btn>

        <v-menu
          open-on-hover
          offset-y
          bottom
          v-if="!authenticated"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              v-bind="attrs"
              v-on="on"
              text 
              class="hidden-sm-and-down"
            >
              <v-icon>mdi-account-plus</v-icon>
              Sign Up
            </v-btn>
          </template>
    
          <v-list>
            <v-list-item
              v-for="(item, index) in signup_item"
              :key="index"
              :to="item.link"
              link
            >
              <v-list-item-icon>
                <v-icon small>
                  mdi-{{ item.icon }}
                </v-icon>
              </v-list-item-icon>
              <v-list-item-title>
                {{ item.title }}
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-btn v-if="authenticated" text class="hidden-sm-and-down" @click.prevent="signOut">
          <v-icon>mdi-logout</v-icon>
          Sign Out
        </v-btn>
      </v-app-bar>
      <v-navigation-drawer
        v-model="drawer"
        fixed
        temporary
        left
      >
        <v-list>
          <v-list-item
            link
            to="/signIn"
          >
            <v-list-item-icon>
              <v-icon small>
                mdi-login
              </v-icon>
            </v-list-item-icon>
            Login
          </v-list-item>
          <v-list-group
            :value="true"
            prepend-icon="mdi-account-plus"
          >
            <template v-slot:activator>
              <v-list-item-title>Sign Up</v-list-item-title>
            </template>
  
            <v-list-item
              v-for="(item, i) in signup_item"
              :to="item.link"
              :key="i"
              link
            >
              <v-list-item-icon class="ml-10">
                <v-icon>mdi-{{ item.icon }}</v-icon>
              </v-list-item-icon>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
          </v-list-group>
          <v-list-item
            link
            to="/signOut"
          >
            <v-list-item-icon>
              <v-icon small>
                mdi-logout
              </v-icon>
            </v-list-item-icon>
            Logout
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
      <router-view />
    </v-app>
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex';
export default {
  data() {
    return {
      selectedItem: 1,
      drawer: false,
      signup_item: [
        { title: 'As Buyer', link:'/user/register/buyer', icon: 'account' },
        { title: 'As Seller', link:'/user/register/seller', icon: 'teach' }
      ],
    }
  },
  computed: {
    ...mapGetters({
      authenticated: 'auth/authenticated',
      count: 'order/orders_count',
      user: 'auth/user',
    }),
    currentRouteName() {
      return this.$route.name;
    }
  },
  mounted() {
    this.cartCount((this.user == null) ? 0 : this.user.id)
  },
  methods: {
    ...mapActions({signOutAction: 'auth/signOut', cartCount: 'order/fetchCartCount'}),
    async signOut() {
      await this.signOutAction()
    }
  }
}
</script>