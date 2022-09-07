<!--系统管理 - 角色设置-->

<template>

    <div id="roles">

        <poemAdmin>

            <el-card class="box-card">
                <el-button @click="newRoleFlag = !newRoleFlag" icon="fa fa-user-plus" type="primary" size="small">&nbsp;{{$t('admin.roles.add')}}</el-button>
                <el-button @click="roleChange(2)" icon="fa fa-toggle-on" type="warning" size="small">&nbsp;{{$t('admin.roles.batch')}}{{$t('admin.roles.stop')}}</el-button>
                <el-button @click="roleChange(1)" icon="fa fa-toggle-off" type="success" size="small">&nbsp;{{$t('admin.roles.batch')}}{{$t('admin.roles.open')}}</el-button>
                <el-button @click="roleChange(0)" icon="fa fa-user-times" type="danger" size="small">&nbsp;{{$t('admin.roles.batch')}}{{$t('admin.roles.del')}}</el-button>
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
                        width="220"
                        fixed="left"
                        :label="$t('admin.roles.operation')">
                        <template slot-scope="scope">
                            <el-button
                                size="mini"
                                type="primary"
                                :title="$t('admin.roles.edit')"
                                @click="editRoleNow(scope)">
                                <i class="fa fa-pencil">&nbsp;{{$t('admin.roles.edit')}}</i>
                            </el-button>
                            <el-button
                                size="mini"
                                type="warning"
                                :title="$t('admin.roles.stop')"
                                v-if="scope.row.status == 1"
                                :disabled="scope.row.name == 'admin'"
                                @click="roleChange(2, scope)">
                                <i class="fa fa-toggle-on">&nbsp;{{$t('admin.roles.stop')}}</i>
                            </el-button>
                            <el-button
                                size="mini"
                                type="success"
                                :title="$t('admin.roles.open')"
                                v-if="scope.row.status == 2"
                                :disabled="scope.row.name == 'admin'"
                                @click="roleChange(1, scope)">
                                <i class="fa fa-toggle-off">&nbsp;{{$t('admin.roles.open')}}</i>
                            </el-button>
                            <el-button
                                size="mini"
                                type="danger"
                                :title="$t('admin.roles.del')"
                                :disabled="scope.row.name == 'admin'"
                                @click="roleChange(0, scope)">
                                <i class="fa fa-user-times">&nbsp;{{$t('admin.roles.del')}}</i>
                            </el-button>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="id"
                        sortable
                        fixed
                        width="100"
                        label="ID">
                    </el-table-column>
                    <el-table-column
                        prop="name"
                        sortable
                        width="150"
                        :label="$t('admin.roles.name')">
                    </el-table-column>
                    <el-table-column
                        prop="menu_ids"
                        sortable
                        :label="$t('admin.roles.power')">
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
                        :label="$t('admin.roles.ctime')">
                    </el-table-column>
                    <el-table-column
                        prop="utime"
                        sortable
                        width="160"
                        :label="$t('admin.roles.utime')">
                    </el-table-column>
                    <el-table-column
                        prop="status_mean"
                        sortable
                        width="90"
                        :label="$t('admin.roles.status')">
                    </el-table-column>
                </el-table>
                <div class="pagination" v-html="pageHtml"></div>
            </el-card>

            <!--添加角色模态框-->
            <el-dialog
                :title="$t('admin.roles.add')"
                :visible.sync="newRoleFlag"
                width="50%">
                <el-form ref="form" :model="form" label-width="100px" size="small">
                    <el-form-item :label="$t('admin.roles.name')">
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.roles.power')">
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
            <el-button size="mini" @click="newRoleFlag = false">{{$t('admin.roles.cancel')}}</el-button>
            <el-button size="mini" type="primary" @click="createRoleNow">{{$t('admin.roles.save')}}</el-button>
        </span>
            </el-dialog>

            <!--编辑角色模态框-->
            <el-dialog
                :title="$t('admin.roles.edit')"
                :visible.sync="editRoleFlag"
                width="50%">
                <el-form ref="editForm" :model="editForm" label-width="100px" size="small">
                    <el-form-item :label="$t('admin.roles.name')">
                        <el-input v-model="editForm.name"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.roles.power')">
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
            <el-button size="mini" @click="editRoleFlag = false">{{$t('admin.roles.cancel')}}</el-button>
            <el-button size="mini" type="primary" @click="saveRole">{{$t('admin.roles.save')}}</el-button>
        </span>
            </el-dialog>
        </poemAdmin>
    </div>
</template>

<script>

    import helper from "../../mixins/helper";

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

                //状态
                if(column.label === this.$t('admin.roles.status')) {

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

                if(!this.form.name) {
                    this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.roles.enterName'), type:'warning' });
                    return false;
                }

                this.form.menu_ids = this.$refs.newTree.getCheckedKeys();
                if(this.form.menu_ids.length === 0) {
                    this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.roles.selectMenu'), type:'warning' });
                    return false;
                }

                this.poemRequest({
                    type: 'post',
                    url: '/admin/roles?api=add_role',
                    data: {form:this.form},
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.$notify({
                                title: this.$t('admin.public.success'),
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

                if(!this.editForm.name) {
                    this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.roles.enterName'), type:'warning' });
                    return false;
                }

                this.editForm.menu_ids = this.$refs.editTree.getCheckedNodes(false, 2);

                let temp_ids = [];
                for (let key in this.editForm.menu_ids) {
                    temp_ids.push(this.editForm.menu_ids[key].id);
                }
                this.editForm.menu_ids = temp_ids;

                if(this.editForm.menu_ids.length === 0) {
                    this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.roles.selectMenu'), type:'warning' });
                    return false;
                }

                this.poemRequest({
                    type: 'post',
                    url: '/admin/roles?api=edit_role',
                    data: {form:this.editForm},
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.$notify({
                                title: this.$t('admin.public.success'),
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
                        this.$notify({message:this.$t('admin.public.checkRec'), type:'warning'});
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
                    this.$message({ message:this.$t('admin.public.checkRec'), type:'warning' });
                    return false;
                }

                this.poemRequest({
                    type: 'post',
                    url: '/admin/roles?api=status_change',
                    data: {ids:ids, status:status},
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.$notify({
                                title: this.$t('admin.public.success'),
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

<style lang="less" scoped>

</style>
