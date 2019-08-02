import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);


import Login from './components/Login';
import Home from './components/Home';
import Quotation from './components/Quotation';
import Profile from './components/Profile';


const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            requiresVisitor: true
        }
    },
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/quotation',
        name: 'quotation',
        component: Quotation,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: {
            requiresAuth: true
        }
    }
];


export default new VueRouter({
    history: true,
    mode: 'history',
    routes
})