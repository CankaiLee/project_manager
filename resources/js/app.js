/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Antd from 'ant-design-vue'
import 'ant-design-vue/dist/antd.css'
import App from './App.vue'
import router from './router'
import store from './store'
import base from './base'
import axios from 'axios'
Vue.config.productionTip = false;
Vue.prototype.$http = axios;

Vue.use(Antd);
Vue.use(base);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// 请求拦截器 在请求头加上token
axios.interceptors.request.use(
    request => {
        console.log(router.currentRoute.path);
        if ( '/user/login' !== router.currentRoute.path ) {
            const token = localStorage.getItem('token');
            if (token) {
                request.headers.authorization = token;
            } else {
                router.replace('/user/login');
            }
        }
        return request;
    },
    err => {
        return Promise.reject(err)
    }
);

// 响应拦截器 判断token是否过期
axios.interceptors.response.use(
    response => {
        if (response.data.code === 40001 || response.data.code === '40001') {
            store.commit('logout');
            router.replace('/user/login');
        }

        return response;
    },
    error => {
        return Promise.reject(error);
    }
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});
