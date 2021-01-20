import Vue from 'vue';
import Router from 'vue-router';
import { store } from './store'
// Main App Pages
import Home from './components/pages/Home';
import Cart from './components/pages/Cart';
import Payment from './components/pages/PaymentSuccess';
import Checkout from './components/pages/Checkout';
import PageNotFound from './components/pages/PageNotFound';
import ProductInfo from './components/pages/ProductInfo';
// Admin pages
import signIn from './admin/Login';
import Register from './admin/Register';
import AdminDashboard from './admin/pages/AdminDashboard';
import Post from './admin/pages/Post';
import Accounts from './admin/pages/Accounts';
import AdminHome from './admin/Admin';
// Register Pages
import Buyer from './admin/pages/register/Buyer';
import Seller from './admin/pages/register/Seller';
// Seller Pages
import SellerDashboard from './seller/pages/SellerDashboard';
import Product from './seller/pages/Product';
import Report from './seller/pages/Report';
import SellerHome from './seller/Seller';
import PostProduct from './seller/pages/Post';

Vue.use(Router);

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/payment',
        name: 'Payment',
        component: Payment,
        beforeEnter: (to, from, next) => {
            if(store.state.auth.authenticated == false) {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '/checkout',
        name: 'Checkout',
        component: Checkout,
        beforeEnter: (to, from, next) => {
            if(store.state.auth.authenticated == false) {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '/cart',
        name: 'Cart',
        component: Cart,
        beforeEnter: (to, from, next) => {
            if(store.state.auth.authenticated == false) {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '/product-info/:id',
        name: 'ProductInfo',
        component: ProductInfo,
        beforeEnter: (to, from, next) => {
            if(store.state.auth.authenticated == false) {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '*',
        name: 'PageNotFound',
        component: PageNotFound,
    },
    {
        path: '/admin/:id', 
        name: 'Admin', 
        component: AdminHome,
        children: [
            {
                name: 'AdminHome',
                path: 'home',
                component: AdminDashboard
            },
            {
                name: 'AdminPost',
                path: 'post',
                component: Post
            },
            {
                name: 'Accounts',
                path: 'accounts',
                component: Accounts
            }
        ],
        beforeEnter: (to, from, next) => {
            if(store.state.auth.authenticated == false) {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '/seller/:id', 
        name: 'Seller', 
        component: SellerHome,
        children: [
            {
                name: 'SellerHome',
                path: 'home',
                component: SellerDashboard
            },
            {
                name: 'SellerProduct',
                path: 'product',
                component: Product
            },
            {
                name: 'SellerPost',
                path: 'post',
                component: PostProduct
            },
            {
                name: 'SellerReport',
                path: 'report',
                component: Report
            }
        ],
        beforeEnter: (to, from, next) => {
            if(store.state.auth.authenticated == false || store.state.auth.user.user_type !== "Seller") {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '/signIn', 
        name: 'signIn', 
        component: signIn,
        beforeEnter: (to, from, next) => {
            if(store.state.auth.authenticated == true) {
                next('/');
            } else {
                next();
            }
        }
    },
    {
        path: '/user/:id', 
        component: Register,
        children: [
            {
                name: 'UserBuyer',
                path: 'Buyer',
                component: Buyer
            },
            {
                name: 'UserSeller',
                path: 'Seller',
                component: Seller
            }
        ],
        beforeEnter: (to, from, next) => {
            if(store.state.auth.authenticated == true) {
                next('/');
            } else {
                next();
            }
        }
    }
];

const router = new Router({
    mode: 'history', 
    base: process.env.BASE_URL, 
    routes
});

export default router;