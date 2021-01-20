<template>
    <v-app id="inspire">
        <v-navigation-drawer
            enable-resize-watcher 
            app
            v-model="drawer"
            class="grey lighten-5"
        >
            <v-list>
                <v-list-item
                >
                    <v-list-item-content>
                        <v-list-item-title class="title">
                        {{ user.name }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
    
            <v-divider></v-divider>
    
            <v-list
                nav
                dense
            >
                <v-list-item 
                    link
                    v-for="item in items"
                    :key="item.id"
                    :to="item.link"
                >
                    <v-list-item-icon>
                        <v-icon>mdi-{{ item.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar
            color="deep-purple"
            fixed
            app
            dark
        >
            <v-btn 
                icon 
                class="grey--text" 
                @click.stop="drawer = !drawer"
            >
                <v-icon>mdi-menu</v-icon>
            </v-btn>
            <v-toolbar-title class="text-uppercase white--text">
                <span class="font-weight-thin">Seller</span>
                <span class="font-weight-black">Dashboard</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn text class="hidden-sm-and-down">
                <v-icon>mdi-bell</v-icon>
            </v-btn>
            <v-btn v-if="authenticated" text class="hidden-sm-and-down" @click.prevent="signOut">
                <v-icon>mdi-logout</v-icon>
                Sign Out
            </v-btn>
        </v-app-bar>
        <v-main>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
import { mapGetters, mapActions } from 'vuex';
export default {
  data() {
        return {
            selectedItem: 1,
            drawer: false,
            items: [
                { title: 'Dashboard', link:'/seller/dashboard/home', icon: 'view-dashboard' },
                { title: 'Products', link:'/seller/dashboard/product', icon: 'package-variant-closed' },
                { title: 'Post', link:'/seller/dashboard/post', icon: 'post' },
                { title: 'Reports', link:'/seller/dashboard/report', icon: 'order-bool-ascending-variant' },
            ]
        }
    },
    computed: {
        ...mapGetters({
            authenticated: 'auth/authenticated',
            user: 'auth/user',
        }),
        currentRouteName() {
            return this.$route.path;
        }
    },
    methods: {
        ...mapActions({signOutAction: 'auth/signOut'}),
        async signOut() {
            await this.signOutAction()
        }
    }
}
</script>