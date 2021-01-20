import axios from "axios"

export default {
    namespaced: true,

    state: {
        payments: [],
    },

    getters: {
        payments (state) {
            return state.payments
        },
    },

    mutations: {
        SET_PAYMENTS (state, value) {
            state.payments = value
        },
    },

    actions: {
        async fetchPayments({ commit }, value) {
            const res = await axios.get(`/api/payments`)
            commit('SET_PAYMENTS', res.data)
        },
        
        async addPayment({ commit }, {order_id, payment_typeid}) {
            const res = await axios.get(`/api/payment/checkout/${order_id}/payment_type/${payment_typeid}`)
        },
    }
}