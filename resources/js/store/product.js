import axios from "axios"

export default {
    namespaced: true,

    state: {
        products: [],
        sellerProd: []
    },

    getters: {
        allProducts (state) {
            return state.products
        },
        sellerProducts (state) {
            return state.sellerProd
        },
    },

    mutations: {
        SET_PRODUCTS (state, value) {
            state.products = value
        },

        SET_SELLERPRODUCTS (state, value) {
            state.sellerProd = value
        }

    },

    actions: {
        async fetchProducts({ commit }) {
            const res = await axios.get('/api/products')
            commit('SET_PRODUCTS', res.data)
        },

        async fetchSellerProducts({ commit }, value) {
            const res = await axios.get(`/api/products/seller/${value}`)
            commit('SET_SELLERPRODUCTS', res.data)
        },

        async addProduct({ commit }, products) {
            const res = await axios.post('/api/product', products)
        },

        async updateProduct({ commit }, products) {
            const res = await axios.put('/api/product', products)
        }
    }
}