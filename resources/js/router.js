import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    routes: [
        {
            name: 'user',
            path: '/user',
            redirect: '/user/login',
            component: App,
            children: [
                {
                    name: 'Login',
                    path: '/user/login',
                    component: resolve => void (require(['./pages/user/Login.vue'], resolve)),
                }
            ]
        },
        {
            name: 'dashboard',
            path: '/dashboard',
            redirect: '/dashboard/workplace',
            component: App,
            children: [
                {
                    name: 'Workplace',
                    path: '/dashboard/workplace',
                    component: resolve => void(require(['./pages/dashboard/Workplace.vue'], resolve)),
                }
            ]
        },
        {
            name: 'menu',
            path: '/menu',
            redirect: '/menu/all',
            component: App,
            children: [
                {
                    name: 'MenuList',
                    path: '/menu/all',
                    component: resolve => void (require(['./pages/menu/MenuList.vue'], resolve)),
                },
                {
                    name: 'MenuDetail',
                    path: '/menu/detail',
                    component: resolve => void (require(['./pages/menu/MenuDetail.vue'], resolve)),
                }
            ]
        }
    ]
});

router.beforeEach((to, from , next) => {
    if ('/user/login' === to.path) {
        next();
    } else {
        let token = localStorage.getItem('token');

        if ('null' === token || null === token || '' === token || undefined === token) {
            next('/user/login');
        } else {
            next();
        }
    }
});

export default router
