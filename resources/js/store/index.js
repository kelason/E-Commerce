import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth'
import category from './category'
import product from './product'
import post from './post'
import order from './order'
import payment from './payment'

Vue.use(Vuex)

export const store = new Vuex.Store({
  state: {
    layout: 'main-layout'
  },
  mutations: {
    SET_LAYOUT (state, payload) {
      state.layout = payload
    }
  },
  getters: {
    layout (state) {
      return state.layout
    }
  },
  modules: {
    auth,
    category,
    product,
    post,
    order,
    payment
  }
})