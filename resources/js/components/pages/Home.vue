<template>
  <v-app id="inspire"
    class="mt-14"
  >
    <v-carousel 
      hide-delimiters
      height="auto"
    >
      <v-carousel-item
        v-for="(item,i) in items"
        :key="i"
        :src="item.src"
        touch
        eager
      ></v-carousel-item>
    </v-carousel>
    <v-row
      no-gutters
    >
      <v-col
        class="hidden-md-and-down"
      >
        <v-img
          src="/images/banner1-cr-500x150.jpg"
          class="ml-auto"
          max-width="500"
        >
        </v-img>
      </v-col>
      <v-col
        class="hidden-md-and-down"
      >
        <v-img
          src="/images/banner2-cr-500x150.jpg"
          class="mr-auto"
          max-width="500"
        >
        </v-img>
      </v-col>
    </v-row>

    <v-container>
      <v-row>
        <v-col>
          <div class="text-h6 font-weight-black my-5">
            Top Products
          </div>
          <v-divider></v-divider>
        </v-col>
      </v-row>
      <v-row>
        <v-col
          v-for="(post, i) in allPosts"
          :key="i"
          col="12"
          lg="3"
          md="4"
          sm="6"
        >
            <v-hover
              v-slot="{ hover }"
            >
              <v-card
                :elevation="hover ? 12 : 2"
                :class="{ 'on-hover': hover }"
                min-width="250"
                style="cursor: pointer;"
              >
                <router-link
                  class="text-decoration-none"
                  :to="`/product-info/${post.post_product.product_id}`"
                >
                  <v-img
                    height="250"
                    :src="`/images/products/${post.post_product.product_image}`"
                  ></v-img>
              
                  <v-card-title
                    class="font-weight-bold subtitle-1"
                  >
                    {{ post.title | toTitle }}
                  </v-card-title>
              
                  <v-card-text>
                    <v-row
                      align="center"
                      class="mx-0"
                    >
                      <div class="my-4 subtitle-1">
                        {{ post.post_product.product_price | toCurrency }}
                      </div>
                    </v-row>
                    <v-row
                      align="center"
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
                        4.5 (413)
                      </div>
                    </v-row>
                  </v-card-text>
                </router-link>
                <v-divider class="mx-4"></v-divider>
            
                <v-card-actions>
                  <v-btn
                    color="deep-purple"
                    dark
                    block
                    @click="addToCart(post)"
                    v-if="post.post_product.order_status === null || post.post_product.buyer_id != (user === null ? 0 : user.id) || post.post_product.order_status == 'Sold'"
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
                </v-card-actions>
              </v-card>
            </v-hover>
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
      items: [
        {
          src: '/images/Banner1.png',
        },
        {
          src: '/images/Banner2.png',
        }
      ],
    }
  },
  computed: {
      ...mapGetters({
          allPosts: 'post/allPosts',
          user: 'auth/user',
      })
  },
  mounted() {
    this.showPost((this.user == null) ? 0 : this.user.id)
  },
  created() {
    this.$store.commit('SET_LAYOUT', 'main-layout')
  },
  methods: {
    ...mapActions({sellerProducts: 'product/fetchSellerProducts', addCart: 'order/addToCart', showPost: 'post/fetchApprovedPosts', cartCount: 'order/fetchCartCount'}),
    addToCart(post) {
      if(this.user === null || this.user.user_type != "Buyer") {
        alert('Please Login first before adding a product')
      } else {
        const app = this
        var post = {...post, ...{buyer_id: (app.user == null) ? 0 : app.user.id}}
        app.addCart(post)
        app.showPost((app.user == null) ? 0 : app.user.id)
        app.cartCount((app.user == null) ? 0 : app.user.id)
      }
    }
  }
}
</script>

<style>

</style>