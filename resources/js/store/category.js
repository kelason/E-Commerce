import axios from "axios"

export default {
    namespaced: true,

    state: {
        categories: null,
    },

    getters: {
        allCategories (state) {
            return state.categories
        },
    },

    mutations: {
        SET_CATEGORIES (state, value) {
            state.categories = value
        }

    },

    actions: {
        async fetchCategories ({ commit }) {
            const res = await axios.get('/api/categories')
            commit('SET_CATEGORIES', res.data)
        },
    }
}