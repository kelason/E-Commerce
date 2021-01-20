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
                                    Post Product
                                </v-col>
                                <v-col
                                    offset-lg="6"
                                    lg="1"
                                >
                                    <v-dialog
                                        v-model="dialog"
                                        persistent
                                        max-width="600px"
                                    >
                                        <v-card>
                                            <v-form 
                                                method="POST" 
                                                v-model="valid"
                                                @submit.prevent="addPost"
                                            >
                                                <v-card-title>
                                                    <span class="headline">Add Post</span>
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-container>
                                                            <v-row>
                                                                <v-col
                                                                    cols="12"
                                                                    sm="12"
                                                                    md="6"
                                                                >
                                                                    <v-text-field
                                                                        label="Post Title"
                                                                        v-model="posts.title"
                                                                        required
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    sm="12"
                                                                    md="6"
                                                                >
                                                                    <v-select
                                                                        :items="sellerProd"
                                                                        v-model="posts.product_id"
                                                                        :rules="[(v) => !!v || 'product is required']"
                                                                        label="Product"
                                                                        item-text="product_name"
                                                                        item-value="product_id"
                                                                    ></v-select>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    sm="12"
                                                                >
                                                                    <v-textarea
                                                                        name="input-7-1"
                                                                        filled
                                                                        :rules="[(v) => !!v || 'Post descripton is required']"
                                                                        label="Post Description"
                                                                        v-model="posts.description"
                                                                        auto-grow
                                                                    ></v-textarea>
                                                                </v-col>
                                                            </v-row>
                                                    </v-container>
                                                    <small>*indicates required field</small>
                                                </v-card-text>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn
                                                        color="blue darken-1"
                                                        text
                                                        @click="dialog = false"
                                                    >
                                                    Close
                                                    </v-btn>
                                                    <v-btn
                                                        color="blue darken-1"
                                                        text
                                                        :disabled="!valid"
                                                        type="submit"
                                                    >
                                                    Save
                                                    </v-btn>
                                                </v-card-actions>
                                            </v-form>
                                        </v-card>
                                    </v-dialog>
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
                                                    Post Title
                                                </th>
                                                <th class="text-left">
                                                    Post Description
                                                </th>
                                                <th class="text-left">
                                                    Product Name
                                                </th>
                                                <th class="text-left">
                                                    Post Status
                                                </th>
                                                <th class="text-left">
                                                    Approve
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="post in allPosts"
                                                :key="post.post_id"
                                            >
                                                <td>{{ post.title }}</td>
                                                <td>{{ post.description | toString }}</td>
                                                <td>{{ post.post_product.product_name }}</td>
                                                <td>{{ post.status }}</td>
                                                <td>
                                                    <v-btn
                                                        fab
                                                        dark
                                                        small
                                                        color="indigo"
                                                        @click="approvePost(post.post_id)"
                                                        v-if="post.status == 'Pending'"
                                                    >
                                                        <v-icon 
                                                            dark
                                                            small
                                                        >
                                                            mdi-check-decagram
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
  props: {
  },
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
            sellerProd: 'product/sellerProducts',
            allPosts: 'post/allPosts',
            user: 'auth/user',
        })
    },
    mounted() {
        this.$store.dispatch('post/fetchPosts')
        this.sellerProducts(this.user.id)
    },
    created() {
    },
    methods: {
        ...mapActions({sellerProducts: 'product/fetchSellerProducts', postAction: 'post/addPost', postUpdate: 'post/updatePost', postApprove: 'post/approveStatus'}),
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
        }
    }
}
</script>

<style>

</style>