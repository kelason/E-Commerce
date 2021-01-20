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
                                    Post Product
                                </v-col>
                                <v-col
                                    offset-lg="8"
                                    lg="1"
                                >
                                    <v-dialog
                                        v-model="dialog"
                                        persistent
                                        max-width="600px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                                class="mx-2"
                                                fab
                                                dark
                                                color="indigo"
                                                v-bind="attrs"
                                                v-on="on"
                                                @click="posts=[]"
                                            >
                                                <v-icon dark>
                                                    mdi-plus
                                                </v-icon>
                                            </v-btn>
                                        </template>
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
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="post in sellerPosts"
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
                                                        @click="editPost(post)"
                                                    >
                                                        <v-icon 
                                                            dark
                                                            small
                                                        >
                                                            mdi-square-edit-outline
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
            sellerPosts: 'post/seller_posts',
            sellerProd: 'product/sellerProducts',
            user: 'auth/user',
        })
    },
    mounted() {
        this.$store.dispatch('post/fetchPosts')
        this.sellerPost(this.user.id)
        this.sellerProducts(this.user.id)
    },
    created() {
        
    },
    methods: {
        ...mapActions({sellerProducts: 'product/fetchSellerProducts', sellerPost: 'post/fetchSellerPosts', postAction: 'post/addPost', postUpdate: 'post/updatePost'}),
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
            this.sellerPost(this.user.id)
            app.posts =[]
            app.dialog = false
        }
    }
}
</script>

<style>

</style>