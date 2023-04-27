import Vue from 'vue';
import VueRouter from 'vue-router';

import Products from './components/Products';
import ProductsCard from './components/ProductsCard';
import Categories from './components/Categories';
import Brands from './components/Brands';
import AppNotFound from './components/AppNotFound';

Vue.use(VueRouter);

let routes = [
    { name: 'products', path: '/', component: Products },
    { name: 'productsCard', path: '/products/:id', component: ProductsCard },
    { name: 'categories', path: '/categories', component: Categories },
    { name: 'brands', path: '/brands', component: Brands },
    { name: 'notFound', path: '*', component: AppNotFound}
];

export default new VueRouter({
    routes,
    mode: 'history'
});
