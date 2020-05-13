<template>
    <page-view :bindTopMenu="top_menu" :bindSubMenu="sub_menu">
        <a-form-model :model="form" ref="ruleForm" :label-col="labelCol" :wrapper-col="wrapperCol">
            <a-form-model-item label="标题">
                <a-input v-model="form.title" />
            </a-form-model-item>

            <a-form-model-item label="父级菜单">
                <a-select v-model="form.parent_id">
                    <a-select-option v-for="menu in parent_menus" :key="menu.id" :value="menu.id">
                        {{ menu.title }}
                    </a-select-option>
                </a-select>
            </a-form-model-item>

            <a-form-model-item label="Uri">
                <a-input v-model="form.uri" />
            </a-form-model-item>

            <a-form-model-item label="Icon">
                <a-input v-model="form.icon" />
            </a-form-model-item>

            <a-form-model-item label="菜单类型">
                <a-select v-model="form.type" placeholder="please select your zone">
                    <a-select-option v-for="item in type" :key="item.key" :value="item.key">
                        {{ item.value }}
                    </a-select-option>
                </a-select>
            </a-form-model-item>

            <a-form-model-item label="是否启用">
                <a-switch v-model="form.status === 1" />
            </a-form-model-item>

            <a-form-model-item :wrapper-col="{ span: 14, offset: 4 }">
                <a-button type="primary" @click="onSubmit">
                    保存
                </a-button>
                <a-button style="margin-left: 10px;" @click="onReset">
                    取消
                </a-button>
            </a-form-model-item>
        </a-form-model>
    </page-view>
</template>

<script>
    import PageView from "../../components/layouts/PageView";

    const type = [
        {key: 1, value: '页面'},
        {key: 2, value: '权限'}
    ];

    export default {
        name: "MenuDetail",
        components: {PageView},
        data () {
            return {
                top_menu: '菜单管理',
                sub_menu: '菜单详情',

                menu_id: 0,
                mode: 1,
                labelCol: { span: 4 },
                wrapperCol: { span: 14 },
                form: {
                    created_at: '',
                    deleted_at: null,
                    icon: '',
                    id: 0,
                    parent_id: 0,
                    status: 1,
                    title: '',
                    type: 1,
                    updated_at: '',
                    uri: "/#"
                },
                type: type,
                parent_menus: []
            }
        },
        mounted: function() {
            let that = this;
            let menu_id = this.$route.query.id;
            if (menu_id) {
                this.mode = 2;
                this.menu_id = menu_id;
                this.$http.get(
                    '/api/menu/get?id=' + menu_id
                ).then(result => {
                    that.form = result.data.data;
                });
            }

            this.$http.get(
                '/api/menu/parent_menu'
            ).then(result => {
                that.parent_menus = result.data.data
            });
        },
        methods: {
            onSubmit () {
                let that = this;
                let uri = '';
                let form_data = {};

                if (this.mode === 1) {
                    uri = '/api/menu/add';
                } else {
                    uri = '/api/menu/mod';
                    form_data.id = this.menu_id;
                }

                form_data.title = this.form.title;
                form_data.parent_id = this.form.parent_id;
                form_data.icon = this.form.icon;
                form_data.type = this.form.type;
                form_data.status = this.form.status;
                form_data.uri = this.form.uri;

                this.$http.post(
                    uri,
                    form_data
                ).then(result => {
                    if (result.data.success === false) {
                        that.$message.error(result.data.msg);
                        return;
                    }

                    that.$message.success(result.data.msg);
                    that.$router.push('/menu/all');
                });

            },
            onReset () {
                this.$refs.ruleForm.resetFields();
            }
        }
    }
</script>

<style scoped>

</style>
