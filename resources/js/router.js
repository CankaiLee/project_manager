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
        }
    ]
});
