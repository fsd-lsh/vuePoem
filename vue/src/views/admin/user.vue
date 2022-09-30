<!--系统管理 - 用户管理-->

<template>

    <div id="user">

        <vp-admin>

            <el-card class="box-card">
                <el-button @click="newUserFlag = !newUserFlag" icon="fa fa-user-plus" type="primary" size="mini">&nbsp;{{$t('admin.user.add')}}</el-button>
                <el-button @click="userChange(2)" change-type="0" icon="fa fa-toggle-on" type="warning" size="mini">&nbsp;{{$t('admin.user.batch')}}{{$t('admin.user.stop')}}</el-button>
                <el-button @click="userChange(1)" icon="fa fa-toggle-off" type="success" size="mini">&nbsp;{{$t('admin.user.batch')}}{{$t('admin.user.open')}}</el-button>
                <el-button @click="userChange(0)" icon="fa fa-user-times" type="danger" size="mini">&nbsp;{{$t('admin.user.batch')}}{{$t('admin.user.del')}}</el-button>
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
                        :label="$t('admin.user.operation')">
                        <template slot-scope="scope">
                            <el-button
                                size="mini"
                                type="primary"
                                :title="$t('admin.user.edit')"
                                @click="editUserNow(scope)">
                                <i class="fa fa-pencil">&nbsp;{{$t('admin.user.edit')}}</i>
                            </el-button>
                            <el-button
                                size="mini"
                                type="warning"
                                :title="$t('admin.user.stop')"
                                v-if="scope.row.status == 1"
                                :disabled="scope.row.name == 'admin'"
                                @click="userChange(2, scope)">
                                <i class="fa fa-toggle-on">&nbsp;{{$t('admin.user.stop')}}</i>
                            </el-button>
                            <el-button
                                size="mini"
                                type="success"
                                :title="$t('admin.user.open')"
                                v-if="scope.row.status == 2"
                                :disabled="scope.row.name == 'admin'"
                                @click="userChange(1, scope)">
                                <i class="fa fa-toggle-off">&nbsp;{{$t('admin.user.open')}}</i>
                            </el-button>
                            <el-button
                                size="mini"
                                type="danger"
                                :title="$t('admin.user.del')"
                                :disabled="scope.row.name == 'admin'"
                                @click="userChange(0, scope)">
                                <i class="fa fa-user-times">&nbsp;{{$t('admin.user.del')}}</i>
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
                        width="130"
                        :label="$t('admin.user.account')">
                    </el-table-column>
                    <el-table-column
                        prop="roles"
                        sortable
                        :label="$t('admin.user.role')">
                        <template slot-scope="scope">
                            <el-tag
                                v-for="role in scope.row.roles_mean"
                                :key="role"
                                size="mini"
                                :type="'info'"
                                style="margin:2px 5px;"
                                effect="dark">
                                {{role}}
                            </el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="ctime"
                        sortable
                        width="160"
                        :label="$t('admin.user.ctime')">
                    </el-table-column>
                    <el-table-column
                        prop="utime"
                        sortable
                        width="160"
                        :label="$t('admin.user.utime')">
                    </el-table-column>
                    <el-table-column
                        prop="status_mean"
                        sortable
                        width="90"
                        :label="$t('admin.user.status')">
                    </el-table-column>
                </el-table>
                <div class="pagination" v-html="pageHtml"></div>
            </el-card>

            <!--创建用户模态框-->
            <el-dialog
                :title="$t('admin.user.add')"
                :visible.sync="newUserFlag"
                width="50%">
                <el-form ref="form" :model="form" label-width="130px" size="small">
                    <el-form-item :label="$t('admin.user.account')">
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.user.password')">
                        <el-input v-model="form.password"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.user.confirm')+$t('admin.user.password')">
                        <el-input v-model="form.rePassword"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.user.role')">
                        <el-select
                            v-model="form.roles"
                            multiple
                            filterable
                            style="width:100%"
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option
                                v-for="item in rolesConfig"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                                {{item.name}}
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button size="mini" @click="newUserFlag = false">{{$t('admin.user.cancel')}}</el-button>
                    <el-button size="mini" type="primary" @click="createUserNow">{{$t('admin.user.save')}}</el-button>
                </span>
            </el-dialog>

            <!--编辑用户模态框-->
            <el-dialog
                :title="$t('admin.user.edit')"
                :visible.sync="editUserFlag"
                width="50%">
                <el-form ref="editForm" :model="editForm" label-width="100px" size="small">
                    <el-form-item :label="$t('admin.user.account')">
                        <el-input v-model="editForm.name"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.user.password')">
                        <el-input v-model="editForm.password" placeholder="默认不修改"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.user.role')">
                        <el-select
                            v-model="editForm.roles"
                            multiple
                            filterable
                            style="width:100%"
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option
                                v-for="item in rolesConfig"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                                {{item.name}}
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button size="mini" @click="editUserFlag = false">{{$t('admin.user.cancel')}}</el-button>
                    <el-button size="mini" type="primary" @click="saveUser">{{$t('admin.user.save')}}</el-button>
                </span>
            </el-dialog>
        </vp-admin>
    </div>
</template>

<script>

    import helper from "../../mixins/helper";

    export default {

        name: 'user',

        mixins: [helper],

        data() {

            return {

                tableData: [],
                tableSelection: [],

                newUserFlag: false,
                editUserFlag: false,

                form: {
                    name: '',
                    password: '',
                    rePassword: '',
                    roles: [],
                },

                editForm: {
                    name: '',
                    password: '',
                    roles: [],
                },

                rolesConfig: [],

                pageHtml: '',
            }
        },

        created() {

            //加载列表
            this.loadLists(
                this.parseGET()['p']
            );
        },

        watch: {
            $route: {
                handler() {

                    //加载列表
                    this.loadLists(
                        this.parseGET()['p']
                    );
                },
                deep: true,
            }
        },

        methods: {

            //创建用户
            createUserNow() {

                if(!this.form.name) { this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.user.enterAcc'), type:'warning' }); return false; }
                if(!this.form.password) { this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.user.enterPwd'), type:'warning' }); return false; }
                if(!this.form.rePassword) { this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.user.reEnterPwd'), type:'warning' }); return false; }
                if(this.form.password !== this.form.rePassword) { this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.user.pwdErr'), type:'warning' }); return false; }
                if(this.form.roles.length === 0) { this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.user.selRoles'), type:'warning' }); return false; }

                this.poemRequest({
                    type: 'post',
                    url: '/admin/user?api=create_user',
                    data: this.form,
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.loadLists();
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

            //打开编辑
            editUserNow(s) {
                this.editForm = s.row;
                this.editForm.password = '';
                this.editUserFlag = true;
            },

            //保存编辑用户
            saveUser() {

                if(!this.editForm.name) { this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.user.enterAcc'), type:'warning' }); return false; }
                if(this.editForm.roles.length === 0) { this.$notify({title:this.$t('admin.public.warning'), message:this.$t('admin.user.selRoles'), type:'warning' }); return false; }

                this.poemRequest({
                    type: 'post',
                    url: '/admin/user?api=info_change',
                    data: {form:this.editForm},
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.loadLists();
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
            userChange(status, s) {

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
                    url: '/admin/user?api=status_change',
                    data: {ids:ids, status:status},
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.loadLists();
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

            //列表行颜色切换
            tableStyle({row, column, rowIndex, columnIndex}) {

                //状态
                if(column.label === this.$t('admin.user.status')) {

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

            //加载列表
            loadLists(page) {

                page = (!page) ? (page = 1) : (page);

                this.poemRequest({
                    type: 'post',
                    url: '/admin/user?api=load&p=' + page,
                    data: this.$route.query,
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.tableData = res.data.data.lists;
                            this.rolesConfig = res.data.data.roles_config;
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
