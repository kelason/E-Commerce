<template>
    <div>
        <v-app id="inspire">
            <v-row
                no-gutters
            >
                <v-col
                    cols="12"
                    lg="12"
                >
                    <v-card
                    class="mx-auto"
                    outlined
                    >
                        <v-card-title>
                            <v-row>
                                <v-col
                                    col="12"
                                    lg="2"
                                    sm="4"
                                >
                                    User Accounts
                                </v-col>
                            </v-row>
                            <v-card-text>
                                <v-simple-table
                                    fixed-header
                                    height="300px"
                                    dense
                                >
                                    <template v-slot:default>
                                        <thead
                                        >
                                            <tr>
                                                <th class="text-left">
                                                    Account Name
                                                </th>
                                                <th class="text-left">
                                                    Email
                                                </th>
                                                <th class="text-left">
                                                    Phone Number
                                                </th>
                                                <th class="text-left">
                                                    User Type
                                                </th>
                                                <th class="text-left">
                                                    Change User Type
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="user in users"
                                                :key="user.id"
                                            >
                                                <td>{{ user.name }}</td>
                                                <td>{{ user.email }}</td>
                                                <td>{{ user.phone_number }}</td>
                                                <td>{{ user.user_type }}</td>
                                                <td>
                                                    <v-btn
                                                        fab
                                                        dark
                                                        small
                                                        color="indigo"
                                                        @click="changeType(user)"
                                                    >
                                                        <v-icon 
                                                            dark
                                                            small
                                                        >
                                                            mdi-cached
                                                        </v-icon>
                                                    </v-btn>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-card-text>
                        </v-card-title>
                    </v-card>
                </v-col>
            </v-row>
        </v-app>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
    data () {
        return {
            valid: false,
            merge: [],
            posts: {
                title: '',
                description: '',
                seller_id: 0,
                product_id: 0
            },
        }
    },
    computed: {
        ...mapGetters({
            users: 'auth/users',
            user: 'auth/user',
            allPosts: 'post/allPosts',
        })
    },
    mounted() {
        this.$store.dispatch('auth/fetchUsers')
    },
    created() {
    },
    methods: {
        ...mapActions({userChange: 'auth/userChange'}),
        async changeType(user) {
            const app = this
            await app.userChange(user)
            app.$store.dispatch('auth/fetchUsers')
        }
    }
}
</script>

<style>

</style>