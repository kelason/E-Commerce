import axios from 'axios'

export default {
    namespaced: true,

    state: {
        authenticated: false,
        user: [],
        users: [],
        msg: '',
        status_code: ''
    },

    getters: {
        authenticated (state) {
            return state.authenticated
        },

        user (state) {
            return state.user
        },

        users (state) {
            return state.users
        },

        msg (state) {
            return state.msg
        },

        status_code (state) {
            return state.status_code
        }
    },

    mutations: {
        SET_AUTHENTICATED (state, value) {
            state.authenticated = value
        },

        SET_USER (state, value) {
            state.user = value
        },

        SET_USERS (state, value) {
            state.users = value
        },

        SET_MSG (state, value) {
            state.msg = value
        },

        SET_STATUS_CODE (state, value) {
            state.status_code = value
        }

    },

    actions: {
        async fetchUsers ({ commit }) {
            const res = await axios.get('/api/users')
            commit('SET_USERS', res.data)
        },

        async userChange ({ commit }, users) {
            const res = await axios.put('/api/user/change', users)
        },

        async signIn ({ dispatch, commit }, credentials) {
            await axios.get('/sanctum/csrf-cookie')
            const res = await axios.post('/api/login', credentials)
            //localStorage.setItem('authToken', res.data.token)
            commit('SET_MSG', res.data.message)
            return dispatch('meRedirect')
        },

        async signUp ({ commit }, credentials) {
            const res = await axios.post('/api/register', credentials)
            commit('SET_MSG', res.data.message)
            commit('SET_STATUS_CODE', res.data.status_code)
        },
    
        async signOut ({ dispatch }) {
            await axios.post('/api/logout')
            //localStorage.removeItem("authToken")
            window.location = '/'
            return dispatch('me')
        },
    
        me ({ commit }) {
            return axios.get('/api/user').then((response) => {
                commit('SET_AUTHENTICATED', true)
                commit('SET_USER', response.data)
            }).catch(() => {
                commit('SET_AUTHENTICATED', false)
                commit('SET_USER', null)
            })
        },

        meRedirect ({ commit }) {
            return axios.get('/api/user').then((response) => {
                commit('SET_AUTHENTICATED', true)
                commit('SET_USER', response.data)
                
                if(response.data.user_type === 'Admin') {
                    window.location = '/admin/dashboard/home'
                } else if(response.data.user_type === 'Buyer') {
                    window.location = '/'
                } else {
                    window.location = '/seller/dashboard/home'
                }
            }).catch(() => {
                commit('SET_AUTHENTICATED', false)
                commit('SET_USER', null)
            })
        }
    }
}