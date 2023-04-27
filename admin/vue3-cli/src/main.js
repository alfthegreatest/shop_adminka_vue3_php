import Vue from 'vue'
import store from './store'
import App from './components/App.vue'
import axios from 'axios'
import router from './router'

const debug = process.env.NODE_ENV !== 'production';

// Для dev-режима
if (debug) {
    axios.defaults.baseURL = 'http://vue.local';
}

new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
})
