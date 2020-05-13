<template>
    <page-view :bindTopMenu="top_menu" :bindSubMenu="sub_menu">
        <a-form layout="inline" :form="searchForm">
            <a-form-item>
                <a-button icon="plus" @click="addMenu">添加</a-button>
            </a-form-item>

            <a-form-item>
                <a-select style="width: 200px" v-model="searchForm.parent_id" placeholder="父级菜单">
                    <a-select-option v-for="menu in parent_menus" :key="menu.id" :value="menu.id">
                        {{ menu.title }}
                    </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item>
                <a-select style="width: 200px" v-model="searchForm.type" placeholder="菜单类型">
                    <a-select-option v-for="item in type" :key="item.key" :value="item.key">
                        {{ item.value }}
                    </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item>
                <a-input v-model="searchForm.keyword" placeholder="标题或uri" />
            </a-form-item>

            <a-form-item>
                <a-button icon="search" @click="onSearch">查询</a-button>
            </a-form-item>
        </a-form>

        <a-table :columns="columns" :data-source="data" :pagination="pagination">
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

    // 表格表头字段
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

    // 菜单类型
    const type = [
        {key: 1, value: '页面'},
        {key: 2, value: '权限'}
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
                sub_menu: "菜单列表",

                type: type,
                parent_menus: [], // 父级菜单

                searchForm: {
                    parent_id: undefined,
                    type: undefined,
                    keyword: undefined,
                },

                pagination: {
                    current: 1,
                    total: 1,
                    pageSize: 10,
                    onChange: (page, pageSize) => this.onPageChange(page, pageSize)
                }

            }
        },
        mounted: function () {
            let that = this;
            let query_string = this.html_build_query(this.$route.query);
            this.$http.get('/api/menu/all?' + query_string).then(result => {
                    let result_data = result.data.data;
                    that.data = result_data.items;
                    that.pagination.current = parseInt(result_data.page);
                    that.pagination.total = parseInt(result_data.total_result);
                    that.pagination.pageSize = parseInt(result_data.per_page);
            });

            this.$http.get(
                '/api/menu/parent_menu'
            ).then(result => {
                that.parent_menus = result.data.data
            });

            if (this.$route.query.parent_id) {
                this.searchForm.parent_id = this.$route.query.parent_id;
            }

            if (this.$route.query.type) {
                this.searchForm.type = this.$route.query.type;
            }

            if (this.$route.query.keyword) {
                this.searchForm.keyword = this.$route.query.keyword;
            }
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

                    that.$message.success('启用成功');
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

                    that.$message.success('禁用成功');
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
            },
            onSearch () {
                let uri = '/menu/all';
                let query = {};
                let page = this.$route.query.page;
                if (! page) page = 1;
                query.parent_id = this.searchForm.parent_id;
                query.type = this.searchForm.type;
                query.keyword = this.searchForm.keyword;
                query.page = page;

                this.$router.push({
                    path: uri,
                    query: query
                });
                this.reload();
            },
            addMenu () {
                this.$router.push('/menu/add');
            },
            onPageChange () {
                console.log(this.$router.currentRoute.fullPath)
            }
        }
    }
</script>

<style scoped>

</style>
