import Vue from 'vue';
import VueRouter from "vue-router";

import BookList from "@/js/components/BookList";
import Home from "@/js/components/Home";
import Login from "@/js/components/Login";
import Register from "@/js/components/Register";
import MyBooks from "@/js/components/MyBooks";
import Cart from "@/js/components/Cart";
import Profil from "@/js/components/Profile";
import AuthorList from "@/js/components/AuthorList";
import AdminView from "@/js/components/AdminView";


Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/home',
            name: 'home',
            component: Home
        },
        {
            path: '/',
            name: 'home2',
            component: Home
        },
        {
            path: '/list',
            name: 'list',
            component: BookList
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/myBooks',
            name: 'myBooks',
            component: MyBooks
        },
        {
            path: '/warenkorb',
            name: 'warenkorb',
            component: Cart
        },
        {
            path: '/profil',
            name: 'profil',
            component: Profil
        },
        {
            path: '/authorlist',
            name: 'authorlist',
            component: AuthorList
        },
        {
            path: '/admin',
            name: 'admin',
            component: AdminView
        }
    ]
});

export default router;