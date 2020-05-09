<template>
    <page-view>
        <a-table :columns="columns" :data-source="data">
            <span slot="type" slot-scope="type">
                <a-tag v-if="type === 1" color="green">页面</a-tag>
                <a-tag v-else color="green">权限</a-tag>
            </span>

            <span slot="status" slot-scope="status">
                <a-tag v-if="status === 1" color="green">启用</a-tag>
                <a-tag v-else color="volcano">禁用</a-tag>
            </span>

            <span slot="action" slot-scope="text, record">
                <a-button type="primary" icon="edit" title="编辑" @click="mod(record.id)" size="small" />
                <a-button v-if="record.status === 1" type="danger" title="下架" icon="download" size="small" @click="off(record.id)" />
                <a-button v-else type="primary" title="上架" icon="upload" size="small" @click="on(record.id)" />
                <a-button type="danger" icon="delete" title="删除" size="small" @click="del(record.id)" />
            </span>
        </a-table>
    </page-view>
</template>status

<script>
    import PageView from '../../components/layouts/PageView'

    const columns = [
        {
            title: "ID",
            dataIndex: "id",
            key: "id",
            width: 30
        },
        {
            title: "父级菜单",
            dataIndex: "parent_title",
            key: "parent_title",
            width: 80
        },
        {
            title: "菜单标题",
            dataIndex: "title",
            key: "title",
            width: 80
        },
        {
            title: "菜单路径",
            dataIndex: "path",
            key: "path",
            width: 80
        },
        {
            title: "类型",
            dataIndex: "type",
            key: "type",
            scopedSlots: { customRender: 'type' },
            width: 30
        },
        {
            title: "是否启用",
            dataIndex: "status",
            key: "status",
            scopedSlots: { customRender: 'status' },
            width: 30
        },
        {
            title: "添加时间",
            dataIndex: "created_at",
            key: "created_at",
            width: 80
        },
        {
            title: "操作",
            dataIndex: "action",
            key: "action",
            scopedSlots: { customRender: 'action' },
            width: 80
        }
    ];

    export default {
        name: "MenuList",
        components: {PageView},
        inject: ['reload'],
        data () {
            return {
                columns: columns,
                data: [],
                top_menu: "菜单管理",
                sub_menu: "菜单列表"
            }
        },
        mounted: function () {
            let that = this;
            this.$http.get('/api/menu/all')
                .then(result => {
                    that.data = result.data.data.items;
                });
        },
        methods: {
            mod (id) {
                this.$router.push('/menu/detail?id=' + id);
            },
            on (id) {
                let that = this;
                this.$http.post(
                    '/api/menu/set_status',
                    {
                        id: id,
                        status: 1
                    }
                ).then(result => {
                    if (result.data.success === false) {
                        that.$message.error(result.data.msg);
                        return;
                    }

                    that.$message.success('上架成功');
                    that.reload();
                });
            },
            off (id) {
                let that = this;
                this.$http.post(
                    '/api/menu/set_status',
                    {
                        id: id,
                        status: 0
                    }
                ).then(result => {
                    if (result.data.success === false) {
                        that.$message.error(result.data.msg);
                        return;
                    }

                    that.$message.success('下架成功');
                    that.reload();
                });
            },
            del (id) {
                let that = this;
                this.$http.get('/api/menu/del?id=' + id)
                    .then(result => {
                        if (result.data.success === false) {
                            that.$message.error(result.data.msg);
                            return;
                        }

                        that.$message.success('删除成功');
                        that.reload();
                    });
            }
        }
    }
</script>

<style scoped>

</style>
