import 'es6-promise/auto'

import Vue from 'vue'; // Importing Vue Library
window.Vue = Vue;

import VueRouter from 'vue-router'; // importing Vue router library
import router from './router';
Vue.use(VueRouter);

import store from './store';


router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.getters.loggedIn) {
            next({
                name: 'login',
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
        if (store.getters.loggedIn) {
            next({
                name: 'home',
            })
        } else {
            next()
        }
    } else {
        next()
    }
});


Vue.component('App', require('./components/App'));

const app = new Vue({
    el: '#app',
    store,
    router
});