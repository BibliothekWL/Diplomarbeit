import Vue from 'vue';
import VueRouter from "vue-router";

import BookList from "@/js/components/BookList";
import Home from "@/js/components/Home";
import Login from "@/js/components/Login";
import Landing from "@/js/components/Landing";
import Register from "@/js/components/Register";



Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
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
            path: '/landing',
            name: 'landing',
            component: Landing
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        }
    ]
});

export default router;