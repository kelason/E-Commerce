<template>
    <v-app id="inspire"
        class="mt-14"
    >
        <v-container>
            <div class="font-weight-bold mt-8 text-xs-h6 text-lg-h5">
                Product Summary
            </div>
        </v-container>
        <v-container
            v-if="orders.length"
        >   
            <v-row>
                <v-col
                    cols="12"
                    lg="8"
                    md="6"
                    sm="12"
                >
                    <v-container
                        class="mt-1" 
                        v-for="(order, index) in orders"
                        :key="index"
                    >
                        <v-divider></v-divider>
                        <v-row
                            class="mt-1"
                        >
                            <v-col
                                col="12"
                                lg="2"
                                md="4"
                            >
                                <v-img
                                    width="120"
                                    height="120"
                                    :src="`/images/products/${order.order_product.product_image}`"
                                ></v-img>
                            </v-col>
                            <v-col
                                col="12"
                                lg="10"
                                md="8"
                            >
                                <div class="font-weight-bold text-xs-h6 text-lg-h5">
                                    {{ order.order_product.product_name }}
                                </div>
                                <div 
                                    class="text-lg-subtitle-1 mx-0 mt-1"
                                >
                                    by {{ order.order_product.name }}
                                </div>
                                <v-row
                                    class="mx-0"
                                >
                                    
                                    <v-rating
                                        :value="4.5"
                                        color="amber"
                                        dense
                                        half-increments
                                        readonly
                                        size="14"
                                    ></v-rating>
                            
                                    <div class="grey--text ml-4">
                                        4.5 ratings (413)
                                    </div>
                                </v-row>
                                <v-row>
                                    <div class="font-weight-bold text-xs-h6 text-lg-h5 ml-4 mt-4 teal--text">
                                        {{ order.order_product.product_price | toCurrency }}
                                    </div>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-col>
                <v-col
                    cols="12"
                    lg="4"
                    md="6"
                    sm="12"
                >
                    <v-card
                        outlined
                        class="text-center"
                    >
                        <v-card-title>
                            <v-select
                                :items="payments"
                                item-text="payment_name"
                                item-value="payment_typeid"
                                v-model="sel"
                                label="Select Payment"
                                dense
                            >

                            </v-select>
                        </v-card-title>
                        <v-card-title
                            class="font-weight-black"
                        >
                            Sub-Total : <div class="teal--text ml-4">{{ subTotal | toCurrency }}</div> 
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-btn
                                color="orange"
                                dark
                                block
                                @click="payment"
                            >
                                Payment
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-container
            v-else
        >
            <v-row>
                <v-col
                    class="mx-auto"
                    cols="3"
                    lg="3"
                    md="3"
                    sm="3"
                >
                    <router-link
                        class="text-decoration-none"
                        :to="`/`"
                    >
                        <v-btn
                            color="orange"
                            dark
                            block
                        >
                            Shop For More
                        </v-btn>
                    </router-link>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
    data () {
        return {
            sel: {
                payment_typeid: 1,
                payment_name: 'Cash on Delivery'
            },
            dialog: false,
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
            orders: 'order/orders',
            payments: 'payment/payments',
            user: 'auth/user',
        }),
        subTotal: function() {
            var sum=0;
            this.orders.forEach(element => {
                sum += parseFloat(element.order_product.product_price)
            });
            return sum;
        },
    },
    mounted() {
        //var payload = {'buyer_id': this.user.id}
        this.$store.dispatch('order/fetchCarts', this.user.id)
        this.$store.dispatch('payment/fetchPayments')
    },
    created() {
    },
    methods: {
        ...mapActions({postProducts: 'post/fetchPostProductInfo', cartCount: 'order/fetchCartCount'}),
        payment() {
            const app = this
            var payload = {'order_id': this.orders[0].order_id,'payment_typeid': app.sel.payment_typeid}
            app.$store.dispatch('payment/addPayment', payload)
            app.cartCount((app.user == null) ? 0 : app.user.id)
            app.$router.push('/payment')
        }
    }
}
</script>