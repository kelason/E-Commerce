<template>
    <v-app id="inspire"
        class="mt-14"
    >
        <v-container>
            <div class="font-weight-bold mt-8 text-xs-h6 text-lg-h5">
                Shopping Cart
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
                                    <v-btn
                                        icon
                                        color="red"
                                        class="mt-4 ml-auto"
                                        @click="deleteCart(order.order_product.product_id)"
                                    >
                                        <v-icon>mdi-trash-can</v-icon>
                                    </v-btn>
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
                        <v-card-title
                            class="font-weight-black"
                        >
                            Sub-Total {{ subTotal | toCurrency }}
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-btn
                                color="orange"
                                dark
                                block
                                @click="$router.push('/checkout')"
                            >
                                Proceed to Checkout
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
    },
    created() {
    },
    methods: {
        ...mapActions({postProducts: 'post/fetchPostProductInfo', cartCount: 'order/fetchCartCount'}),
        editPost(post) {
            let app = this;

            app.edit = true;
            app.dialog = true;

            app.posts.post_id = post.post_id;
            app.posts.title = post.title;
            app.posts.description = post.description;
            app.posts.seller_id = post.seller_id;
            app.posts.product_id = post.post_product.product_id;
        },
        async addPost(){
            const app = this
            //Merge object
            app.posts = {...app.posts, ...{seller_id: app.user.id}}
            
            if(app.edit == false) {
                await app.postAction(app.posts);
            } else {
                await app.postUpdate(app.posts);
                app.edit = false;
            }
            app.$store.dispatch('post/fetchPosts')
            app.posts =[]
            app.dialog = false
        },
        async approvePost(post_id) {
            const app = this
            await app.postApprove(post_id)
            app.$store.dispatch('post/fetchPosts')
        },
        async deleteCart(cart) {
            const app = this
            var payload = {'product_id': cart,'buyer_id': app.user.id}
            await app.$store.dispatch('order/deleteCart', payload)
            app.$store.dispatch('order/fetchCarts', app.user.id)
            this.cartCount((this.user == null) ? 0 : this.user.id)
        }
    }
}
</script>