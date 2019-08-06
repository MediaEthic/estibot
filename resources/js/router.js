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
            title: 'Connexion Estibot - Application de devis simple et rapide pour les imprimeurs',
            requiresVisitor: true
        }
    },
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            title: 'Liste de devis Estibot - Application de devis simple et rapide pour les imprimeurs',
            requiresAuth: true
        }
    },
    {
        path: '/quotation',
        name: 'quotation',
        component: Quotation,
        meta: {
            title: 'Création d\'un nouveau devis Estibot - Application de devis simple et rapide pour les imprimeurs',
            requiresAuth: true
        }
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: {
            title: 'Compte utilisateur Estibot - Application de devis simple et rapide pour les imprimeurs',
            requiresAuth: true
        }
    }
];


export default new VueRouter({
    history: true,
    mode: 'history',
    routes
})