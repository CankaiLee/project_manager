import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'

Vue.use(VueRouter)

export default new VueRouter({
    routes: [
        {
            name: 'test',
            path: '/test',
            redirect: '/test/example',
            component: App,
            children: [
                {
                    name: 'ExampleComponent',
                    path: '/test/example',
                    component: resolve => void (require(['./components/ExampleComponent.vue'], resolve)),
                },
                {
                    name: 'Workplace',
                    path: '/test/workplace',
                    component: resolve => void(require(['./pages/dashboard/Workplace.vue'], resolve)),
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
                }
            ]
        }
    ]
});
