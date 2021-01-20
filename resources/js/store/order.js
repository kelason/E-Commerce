import axios from "axios"

export default {
    namespaced: true,

    state: {
        orders: [],
        orders_count: []
    },

    getters: {
        orders (state) {
            return state.orders
        },
        orders_count (state) {
            return state.orders_count
        },
    },

    mutations: {
        SET_ORDERS (state, value) {
            state.orders = value
        },
        SET_CART_COUNT (state, value) {
            state.orders_count = value
        },
    },

    actions: {
        async fetchCartCount({ commit }, value) {
            const res = await axios.get(`/api/order/cart-count/${value}`)
            commit('SET_CART_COUNT', res.data)
        },

        async fetchCarts({ commit }, value) {
            const res = await axios.get(`/api/post/cart/${value}`)
            commit('SET_ORDERS', res.data)
        },

        async fetchSales({ commit }, value) {
            const res = await axios.get(`/api/product-sales/${value}`)
            commit('SET_ORDERS', res.data)
        },

        async addToCart({ commit }, orders) {
            const res = await axios.post('/api/order/add-to-cart', orders)
        },

        async deleteCart({ commit }, {product_id, buyer_id}) {
            const res = await axios.get(`/api/order/delete-cart/${product_id}/buyer/${buyer_id}`)
        },

    }
}