<template>
    <a-layout id="components-layout-demo-side" style="min-height: 100vh">
        <a-layout-sider v-model="collapsed" collapsible>
            <div class="logo" />
            <a-menu theme="dark" v-for="side_menu in side_menus" :key="side_menu.title"  :default-selected-keys="['1']" mode="inline">
                <a-sub-menu key="sub1">
                    <span slot="title"><a-icon :type="side_menu.icon" /><span>{{ side_menu.title }}</span></span>
                    <a-menu-item v-for="sub_menu in side_menu.sub_menus" :key="sub_menu.title">
                        <a :href="sub_menu.uri"><span>{{ sub_menu.title }}</span></a>
                    </a-menu-item>
                </a-sub-menu>
            </a-menu>
        </a-layout-sider>
        <a-layout>
            <a-layout-header style="background: #fff; padding: 0" />
            <a-layout-content style="margin: 0 16px">
                <a-breadcrumb style="margin: 16px 0">
                    <a-breadcrumb-item>{{ top_menu }}</a-breadcrumb-item>
                    <a-breadcrumb-item>{{ sub_menu }}</a-breadcrumb-item>
                </a-breadcrumb>
                <div :style="{ padding: '24px', background: '#fff', minHeight: '700px' }">
                    <slot>
                        <router-view></router-view>
                    </slot>
                </div>
            </a-layout-content>
            <a-layout-footer style="text-align: center">
                Ant Design ©2018 Created by Ant UED
            </a-layout-footer>
        </a-layout>
    </a-layout>
</template>
<script>
    export default {
        data () {
            return {
                collapsed: false,
                side_menus: [
                    {
                        icon: '',
                        title: '',
                        uri: '',
                        sub_menus: [
                            {
                                icon: '',
                                title: '',
                                uri: ''
                            }
                        ]
                    }
                ],
                top_menu: '',
                sub_menu: ''
            };
        },
        mounted: function() {
            let uri = '/api/menu/side_menu';
            let that = this;
            this.$http.get(uri).then((response) => {
                if (response.data.success === true) {
                    that.side_menus = response.data.data;
                }
            });
        }
    };
</script>

<style>
    #components-layout-demo-side .logo {
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        margin: 16px;
    }
</style>
