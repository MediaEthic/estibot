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
        component: Login
    },
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/quotation',
        name: 'quotatiion',
        component: Quotation
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile
    }
];


export default new VueRouter({
    history: true,
    mode: 'history',
    routes
})