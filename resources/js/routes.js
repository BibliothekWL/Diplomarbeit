import Vue from 'vue';
import VueRouter from "vue-router";

import BookList from "@/js/components/BookList";
import Home from "@/js/components/Home";

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
       }
   ]
});

export default router;