require('./bootstrap')
import Vue from 'vue'
import App from "./App.vue"

import { store } from './store'
import router from './router'
import Vuetify from 'vuetify'
import axios from 'axios'
import moment from 'moment';

import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/dist/vuetify.min.css'

axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://eshop-env.eba-mgtphvam.us-east-2.elasticbeanstalk.com/'
//axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem("authToken")

Vue.use(Vuetify)

Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('fil-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2
    });
    return formatter.format(value);
});

Vue.filter('toString', function (value) {
    var length = 50;
    var trimmedString = value.substring(0, length);
    return trimmedString + (value.length > 50 ? " ....." : "");
});

Vue.filter('toTitle', function (value) {
    var length = 25;
    var trimmedString = value.substring(0, length);
    return trimmedString + (value.length > 20 ? " ....." : "");
});

Vue.filter('toDate', function (value) {
    return moment(value).format("MMMM DD, YYYY hh:mm A")
});

store.dispatch('auth/me').then(() => {
    new Vue({
        vuetify : new Vuetify(),
        router, // replace routes with router
        store,
        render: h => h(App), //indicate the App component inside render function
    }).$mount("#app")
})