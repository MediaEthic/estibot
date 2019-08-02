import 'es6-promise/auto'

import Vue from 'vue'; // Importing Vue Library
window.Vue = Vue;

import VueRouter from 'vue-router'; // importing Vue router library
import router from './router';
Vue.use(VueRouter);

import store from './store';


Vue.component('App', require('./components/App'));

const app = new Vue({
    el: '#app',
    store,
    router
});