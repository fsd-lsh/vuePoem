<!--系统管理 - 角色设置-->

<template>

    <div id="roles">
        <el-card class="box-card">
            <el-button @click="newRoleFlag = !newRoleFlag" icon="fa fa-user-plus" type="primary" size="small">&nbsp;添加角色</el-button>
            <el-button @click="roleChange(2)" icon="fa fa-toggle-on" type="warning" size="small">&nbsp;批量停用</el-button>
            <el-button @click="roleChange(1)" icon="fa fa-toggle-off" type="success" size="small">&nbsp;批量启用</el-button>
            <el-button @click="roleChange(0)" icon="fa fa-user-times" type="danger" size="small">&nbsp;批量删除</el-button>
            <el-table
                :data="tableData"
                stripe
                size="mini"
                fit
                :highlight-current-row="true"
                :cell-style="tableStyle"
                ref="multipleTable"
                @selection-change="handleSelectionChange"
                style="width:100%">
                <el-table-column
                    type="selection"
                    fixed
                    width="55">
                </el-table-column>
                <el-table-column
                    width="200"
                    fixed="left"
                    label="操作">
                    <template slot-scope="scope">
                        <el-button
                            size="mini"
                            type="primary"
                            title="编辑"
                            @click="editRoleNow(scope)">
                            <i class="fa fa-pencil">&nbsp;编辑</i>
                        </el-button>
                        <el-button
                            size="mini"
                            type="warning"
                            title="停用"
                            v-if="scope.row.status == 1"
                            :disabled="scope.row.name == 'admin'"
                            @click="roleChange(2, scope)">
                            <i class="fa fa-toggle-on">&nbsp;停用</i>
                        </el-button>
                        <el-button
                            size="mini"
                            type="success"
                            title="启用"
                            v-if="scope.row.status == 2"
                            :disabled="scope.row.name == 'admin'"
                            @click="roleChange(1, scope)">
                            <i class="fa fa-toggle-off">&nbsp;启用</i>
                        </el-button>
                        <el-button
                            size="mini"
                            type="danger"
                            title="删除"
                            :disabled="scope.row.name == 'admin'"
                            @click="roleChange(0, scope)">
                            <i class="fa fa-user-times">&nbsp;删除</i>
                        </el-button>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="id"
                    sortable
                    fixed
                    width="100"
                    label="角色ID">
                </el-table-column>
                <el-table-column
                    prop="name"
                    sortable
                    width="150"
                    label="角色名称">
                </el-table-column>
                <el-table-column
                    prop="menu_ids"
                    sortable
                    label="权限">
                    <template slot-scope="scope">
                        <el-tag
                            v-for="m_id in scope.row.menu_ids"
                            :key="m_id"
                            size="mini"
                            :type="'info'"
                            style="margin:2px 5px;"
                            effect="dark">
                            {{m_id}}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="ctime"
                    sortable
                    width="160"
                    label="创建时间">
                </el-table-column>
                <el-table-column
                    prop="utime"
                    sortable
                    width="160"
                    label="更新时间">
                </el-table-column>
                <el-table-column
                    prop="status_mean"
                    sortable
                    width="80"
                    label="状态">
                </el-table-column>
            </el-table>
            <div class="pagination" v-html="pageHtml"></div>
        </el-card>

        <!--添加角色模态框-->
        <el-dialog
            title="添加角色"
            :visible.sync="newRoleFlag"
            width="50%">
            <el-form ref="form" :model="form" label-width="100px" size="small">
                <el-form-item label="角色名称">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="菜单权限">
                    <el-tree
                        :data="menu_config"
                        show-checkbox
                        :default-expand-all="true"
                        ref="newTree"
                        node-key="id">
                    </el-tree>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
            <el-button size="mini" @click="newRoleFlag = false">取消</el-button>
            <el-button size="mini" type="primary" @click="createRoleNow">立即创建</el-button>
        </span>
        </el-dialog>

        <!--编辑角色模态框-->
        <el-dialog
            title="编辑角色"
            :visible.sync="editRoleFlag"
            width="50%">
            <el-form ref="editForm" :model="editForm" label-width="100px" size="small">
                <el-form-item label="角色名称">
                    <el-input v-model="editForm.name"></el-input>
                </el-form-item>
                <el-form-item label="菜单权限">
                    <el-tree
                        :data="menu_config"
                        show-checkbox
                        :default-expand-all="true"
                        :default-checked-keys="editForm.menu_ids"
                        getCheckedKeys
                        ref="editTree"
                        node-key="id">
                    </el-tree>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
            <el-button size="mini" @click="editRoleFlag = false">取消</el-button>
            <el-button size="mini" type="primary" @click="saveRole">保存</el-button>
        </span>
        </el-dialog>
    </div>
</template>

<script>

    import helper from "../mixins/helper";

    export default {

        name: 'roles',

        mixins: [helper],

        data() {

            return {

                tableData: [],
                tableSelection: [],

                newRoleFlag: false,
                editRoleFlag: false,

                form: {
                    name: '',
                    menu_ids: '',
                },

                editForm: {
                    name: '',
                    menu_ids: '',
                },

                menu_config: [],

                pageHtml: '',
            }
        },

        created() {

            //加载列表
            this.loadList(
                this.parseGET()['p']
            );
        },

        watch: {
            $route: {
                handler() {

                    //加载列表
                    this.loadList(
                        this.parseGET()['p']
                    );
                },
                deep: true,
            }
        },

        methods: {

            //列表行颜色切换
            tableStyle({row, column, rowIndex, columnIndex}) {

                if(column.label === '状态') {

                    switch (row.status) {
                        case '0': { return 'background:#e4393c; color:#fff!important;'; }
                        case '1': { return 'background:#009688; color:#fff!important;'; }
                        case '2': { return 'background:#FFB800; color:#fff!important;'; }
                    }
                }
            },

            //表格多选
            handleSelectionChange(val) {
                this.tableSelection = val;
            },

            //打开编辑
            editRoleNow(s) {

                this.editForm = s.row;
                this.$nextTick(() => {
                    this.$refs.editTree.setCheckedKeys(s.row.menu_ids);
                });
                this.editRoleFlag = true;
            },

            //创建用户
            createRoleNow() {

                if(!this.form.name) { this.$notify({title:'警告', message:'请输入姓名 / 账号', type:'warning' }); return false; }

                this.form.menu_ids = this.$refs.newTree.getCheckedKeys();
                if(this.form.menu_ids.length === 0) { this.$notify({title:'警告', message:'请选择角色菜单权限', type:'warning' }); return false; }

                this.poemRequest({
                    type: 'post',
                    url: '/admin/roles?api=add_role',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.$notify({
                                title: '成功',
                                message: res.data.info,
                                type: 'success'
                            });
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            //保存角色
            saveRole() {

                if(!this.editForm.name) { this.$notify({title:'警告', message:'请输入姓名 / 账号', type:'warning' }); return false; }

                this.editForm.menu_ids = this.$refs.editTree.getCheckedNodes(false, 2);

                let temp_ids = [];
                for (let key in this.editForm.menu_ids) {
                    temp_ids.push(this.editForm.menu_ids[key].id);
                }
                this.editForm.menu_ids = temp_ids;

                if(this.editForm.menu_ids.length === 0) {
                    this.$notify({title:'警告', message:'请选择角色菜单权限', type:'warning' });
                    return false;
                }

                this.poemRequest({
                    type: 'post',
                    url: '/admin/roles?api=edit_role',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.$notify({
                                title: '成功',
                                message: res.data.info,
                                type: 'success'
                            });
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            //变更状态
            roleChange(status, s) {

                let ids = [];

                if(s === undefined) {
                    if(this.tableSelection.length === 0) {
                        this.$notify({message:'批量操作前请先勾选记录', type:'warning'});
                        return false;
                    }else {
                        for (let key in this.tableSelection) {
                            ids.push(this.tableSelection[key].id);
                        }
                    }
                }else {
                    ids.push(s.row.id);
                }

                if(ids.length === 0) {
                    this.$message({ message:'操作前请先勾选记录', type:'warning' });
                    return false;
                }

                this.poemRequest({
                    type: 'post',
                    url: '/admin/roles?api=status_change',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.$notify({
                                title: '成功',
                                message: res.data.info,
                                type: 'success'
                            });
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            //加载列表
            loadList(page) {

                page = (!page) ? (page = 1) : (page);

                this.poemRequest({
                    type: 'post',
                    url: '/admin/roles?api=load&p=' + page,
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.tableData = res.data.data.lists;
                            this.menu_config = res.data.data.menu_config;
                            this.pageHtml = res.data.data.page_html;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },
        },
    };
</script>

<style lang="less">

    @import "../../static/css/public";
    .el-table__row .cell { color:unset; }
</style>
