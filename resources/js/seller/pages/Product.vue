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
                                    Products
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
                                                @click="products=[]"
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
                                                @submit.prevent="addProduct"
                                            >
                                                <v-card-title>
                                                    <span class="headline">Add Products</span>
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
                                                                        label="Product Name"
                                                                        v-model="products.product_name"
                                                                        required
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    sm="12"
                                                                    md="6"
                                                                >
                                                                    <v-select
                                                                        :items="categories"
                                                                        v-model="products.category_id"
                                                                        :rules="[(v) => !!v || 'category is required']"
                                                                        label="Category"
                                                                        item-text="category_name"
                                                                        item-value="category_id"
                                                                    ></v-select>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    sm="12"
                                                                    md="6"
                                                                >
                                                                    <v-file-input
                                                                        prepend-icon=""
                                                                        type="file"
                                                                        accept="image/*"
                                                                        label="Product Image"
                                                                        @change="onImageChange"
                                                                    ></v-file-input>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    sm="12"
                                                                    md="6"
                                                                >
                                                                    <v-text-field
                                                                        label="Product Price"
                                                                        v-model="products.product_price"
                                                                        required
                                                                        type="number"
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <!-- <v-col
                                                                    cols="12"
                                                                    sm="12"
                                                                >
                                                                    <v-textarea
                                                                        name="input-7-1"
                                                                        filled
                                                                        :rules="[(v) => !!v || 'Product descripton is required']"
                                                                        label="Product Description"
                                                                        v-model="products.product_description"
                                                                        auto-grow
                                                                    ></v-textarea>
                                                                </v-col> -->
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
                                                    Product Image
                                                </th>
                                                <th class="text-left">
                                                    Product Name
                                                </th>
                                                <th class="text-left">
                                                    Product Price
                                                </th>
                                                <th class="text-left">
                                                    Product Category
                                                </th>
                                                <th class="text-left">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="product in allProducts"
                                                :key="product.product_id"
                                            >
                                                <td>
                                                    <img :src="'/images/products/' + product.product_image"  class="border" style="width: 80px;height: 60px; object-fit: cover;">
                                                </td>
                                                <td>{{ product.product_name }}</td>
                                                <td>{{ product.product_price | toCurrency }}</td>
                                                <td>{{ product.product_category.category_name }}</td>
                                                <td>
                                                    <v-btn
                                                        fab
                                                        dark
                                                        small
                                                        color="indigo"
                                                        @click="editProduct(product)"
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
            products: {
                product_name: '',
                product_image: null,
                product_price: 0,
                seller_id: 0,
                category_id: 0
            },
        }
    },
    mounted: {
        
    },
    computed: {
        ...mapGetters({
            categories: 'category/allCategories',
            allProducts: 'product/sellerProducts',
            user: 'auth/user',
        })
    },
    mounted() {
        this.$store.dispatch('category/fetchCategories')
        this.sellerProducts(this.user.id)
    },
    created() {
        //console.log(this.allProducts)
    },
    methods: {
        ...mapActions({sellerProducts: 'product/fetchSellerProducts', productAction: 'product/addProduct', productUpdate: 'product/updateProduct'}),
        onImageChange(event) {
            this.products.product_image = event;
            this.createImage(this.products.product_image);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.products.product_image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        editProduct(product) {
            let app = this;

            app.edit = true;
            app.dialog = true;

            app.products.product_id = product.product_id;
            app.products.product_name = product.product_name;
            app.products.product_image = product.product_image;
            app.products.product_price = product.product_price;
            app.products.seller_id = product.seller_id;
            app.products.category_id = product.product_category.category_id;
        },
        async addProduct(){
            const app = this
            //Merge object
            app.products = {...app.products, ...{seller_id: app.user.id}}

            if(app.edit == false) {
                await app.productAction(app.products);
            } else {
                await app.productUpdate(app.products);
                app.edit = false;
            }
            this.sellerProducts(this.user.id)
            app.products =[]
            app.dialog = false
        }
    }
}
</script>

<style>

</style>