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
                                    lg="3"
                                >
                                    Sold Products
                                </v-col>
                                <v-col
                                    offset-lg="8"
                                    lg="1"
                                >
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
                                                    Product Image
                                                </th>
                                                <th class="text-left">
                                                    Product Name
                                                </th>
                                                <th class="text-left">
                                                    Product Price
                                                </th>
                                                <th class="text-left">
                                                    Sold Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(sale, index) in sales"
                                                :key="index"
                                            >
                                                <td>
                                                    <img :src="'/images/products/' + sale.product_image"  class="border" style="width: 80px;height: 60px; object-fit: cover;">
                                                </td>
                                                <td>{{ sale.product_name }}</td>
                                                <td>{{ sale.product_price | toCurrency }}</td>
                                                <td>{{ sale.created_at | toDate }}</td>
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
            dialog: false,
            edit: false,
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
            sales: 'order/orders',
            user: 'auth/user',
        })
    },
    mounted() {
        this.sellerProducts(this.user.id)
    },
    created() {
        
    },
    methods: {
        ...mapActions({sellerProducts: 'order/fetchSales'}),
    }
        
}
</script>

<style>

</style>