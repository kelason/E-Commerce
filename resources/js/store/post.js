import axios from "axios"

export default {
    namespaced: true,

    state: {
        posts: [],
        seller_posts: []
    },

    getters: {
        allPosts (state) {
            return state.posts
        },
        seller_posts (state) {
            return state.seller_posts
        }
    },

    mutations: {
        SET_POSTS (state, value) {
            state.posts = value
        },
        SET_SELLERPOSTS (state, value) {
            state.seller_posts = value
        }
    },

    actions: {
        async fetchPosts({ commit }) {
            const res = await axios.post('/api/posts')
            commit('SET_POSTS', res.data)
        },

        async addPost({ commit }, posts) {
            const res = await axios.post('/api/post', posts)
        },

        async updatePost({ commit }, posts) {
            const res = await axios.put('/api/post', posts)
        },

        async fetchSellerPosts({ commit }, value) {
            const res = await axios.get(`/api/post/seller/${value}`)
            commit('SET_SELLERPOSTS', res.data)
        },

        async fetchPostProductInfo({ commit }, {product_id, buyer_id}) {
            const res = await axios.get(`/api/post/product/${product_id}/buyer/${buyer_id}`)
            commit('SET_POSTS', res.data)
        },

        async approveStatus({ commit }, posts) {
            const res = await axios.put('/api/post/status', {'post_id': posts})
        },

        async fetchApprovedPosts({ commit }, value) {
            const res = await axios.get(`/api/post/approved/${value}`)
            commit('SET_POSTS', res.data)
        }
    }
}