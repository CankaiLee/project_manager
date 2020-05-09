<template>
    <div id="app">
        <router-view v-if="isRouterAlive"></router-view>
    </div>
</template>

<script scoped>
    export default {
        data () {
            return {
                isRouterAlive: true
            }
        },
        provide () {
            return {
                reload: this.reload
            }
        },
        components: {

        },
        computed: {},
        methods: {
            reload () {
                this.isRouterAlive = false;
                this.$nextTick(function() {
                    this.isRouterAlive = true;
                });
            }
        },
        mounted() {
        },
    }
</script>

<style scoped>
    html,body {
        margin: 0;
        padding: 0;
    }
</style>
