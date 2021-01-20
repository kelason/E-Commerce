<template>
    <v-app id="inspire"
        class="mt-14"
    >
        <!-- Product id {{ $route.params.id }} -->
        <v-container
           class="mt-14" 
           v-for="post in allPosts"
           :key="post.post_id"
        >
            <v-row>
                <v-col
                    col="12"
                    lg="6"
                    md="5"
                    sm="4"
                >
                    <v-img
                        height="450"
                        :src="`/images/products/${post.post_product.product_image}`"
                    ></v-img>
                </v-col>
                <v-col
                    col="12"
                    lg="6"
                    md="7"
                    sm="8"
                >
                    <div class="font-weight-bold text-xs-h6 text-lg-h5">
                        {{ post.title }}
                    </div>
                    <div 
                        class="text-lg-subtitle-1 mx-0"
                    >
                        by {{ post.post_seller.name }}
                    </div>
                    <v-row
                        align="center"
                        class="mx-0 mt-1"
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
                    <v-divider
                        class="my-5"
                    ></v-divider>
                    <v-row
                        align="center"
                        class="mx-0 mt-1"
                    >
                        <v-col
                            lg="3"
                            md="6"
                        >
                            <div 
                                class="text-lg-subtitle-1 mx-0"
                            >
                                Item Name:
                            </div>
                        </v-col>
                        <v-col
                            lg="9"
                            md="6"
                        >
                            <div 
                                class="font-weight-black text-lg-h6 mx-3"
                            >
                                {{ post.post_product.product_name }}
                            </div>
                        </v-col>
                    </v-row>
                    <v-row
                        align="center"
                        class="mx-0 mt-1"
                    >
                        <v-col
                            lg="3"
                            md="6"
                        >
                            <div 
                                class="text-lg-subtitle-1 mx-0"
                            >
                                Price:
                            </div>
                        </v-col>
                        <v-col
                            lg="9"
                            md="6"
                        >
                            <div 
                                class="font-weight-bold text-lg-h6 mx-3 red--text"
                            >
                                {{ post.post_product.product_price | toCurrency }}
                            </div>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col
                            lg="6"
                            md="6"
                            sm="6"
                            col="12"
                        >
                            <v-btn
                                color="red"
                                dark
                                block
                                @click="$router.push('/checkout')"
                            >
                                PROCEED TO CHECKOUT
                            </v-btn>
                        </v-col>
                        <v-col
                            lg="6"
                            md="6"
                            sm="6"
                            col="12"
                        >
                            <v-btn
                                color="deep-purple"
                                dark
                                block
                                @click="addToCart(post)"
                                v-if="post.post_product.order_status === null || post.post_product.buyer_id != user.id || post.post_product.order_status == 'Sold'"
                            >
                                ADD TO CART
                            </v-btn>
                            <v-btn
                                color="orange"
                                dark
                                block
                                v-else
                            >
                                IN THE CART
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
            <v-row>
                <v-divider
                    class="mt-5 hidden-lg-only"
                ></v-divider>
            </v-row>
            <v-row>
                <v-col>
                    <div class="font-weight-bold text-xs-h6 text-lg-h5 mt-5 mx-0">
                        Product Decription
                    </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-divider
                        class="my-3"
                    ></v-divider>
                    <p class="text-justify mx-0">
                        {{ post.description }}
                    </p>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <div class="font-weight-bold text-xs-h6 text-lg-h5 mt-5 mx-0">
                        Product Ratings & Reviews
                    </div>
                </v-col>
            </v-row>
            <v-row
                align="center"
                class="mx-0 mt-1"
            >
                
                <v-rating
                :value="4.5"
                color="amber"
                half-increments
                readonly
                x-large
                ></v-rating>
        
                <div class="grey--text ml-4">
                4.5 ratings (413)
                </div>
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
            allPosts: 'post/allPosts',
            user: 'auth/user',
        })
    },
    mounted() {
        var payload = {'product_id': this.$route.params.id, 'buyer_id': this.user.id}
        this.$store.dispatch('post/fetchPostProductInfo', payload)
    },
    created() {
    },
    methods: {
        ...mapActions({addCart: 'order/addToCart', cartCount: 'order/fetchCartCount'}),
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
        addToCart(post) {
            if(this.user === null || this.user.user_type != "Buyer") {
                alert('Please Login first before adding a product')
            } else {
                const app = this
                var post = {...post, ...{buyer_id: app.user.id}}
                app.addCart(post)
                var payload = {'product_id': app.$route.params.id, 'buyer_id': app.user.id}
                app.$store.dispatch('post/fetchPostProductInfo', payload)
                app.cartCount((app.user == null) ? 0 : app.user.id)
            }
        },
        async approvePost(post_id) {
            const app = this
            await app.postApprove(post_id)
            app.$store.dispatch('post/fetchPosts')
            this.cartCount((this.user == null) ? 0 : this.user.id)
        }
    }
}
</script>