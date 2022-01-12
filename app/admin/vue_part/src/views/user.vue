<!--系统管理 - 用户管理-->

<include "../public/vue"/>

<style>
    .el-table__row .cell { color:unset; }
</style>

<div id="app">

    <el-card class="box-card">
        <el-button @click="newUserFlag = !newUserFlag" icon="fa fa-user-plus" type="primary" size="small">&nbsp;创建用户</el-button>
        <el-button @click="userChange(2)" change-type="0" icon="fa fa-toggle-on" type="warning" size="small">&nbsp;批量停用</el-button>
        <el-button @click="userChange(1)" icon="fa fa-toggle-off" type="success" size="small">&nbsp;批量启用</el-button>
        <el-button @click="userChange(0)" icon="fa fa-user-times" type="danger" size="small">&nbsp;批量删除</el-button>
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
                fixed
                label="操作">
                <template slot-scope="scope">
                    <el-button
                        size="mini"
                        type="primary"
                        title="编辑"
                        @click="editUserNow(scope)">
                        <i class="fa fa-pencil">&nbsp;编辑</i>
                    </el-button>
                    <el-button
                        size="mini"
                        type="warning"
                        title="停用"
                        v-if="scope.row.status == 1"
                        :disabled="scope.row.name == 'admin'"
                        @click="userChange(2, scope)">
                        <i class="fa fa-toggle-on">&nbsp;停用</i>
                    </el-button>
                    <el-button
                        size="mini"
                        type="success"
                        title="启用"
                        v-if="scope.row.status == 2"
                        :disabled="scope.row.name == 'admin'"
                        @click="userChange(1, scope)">
                        <i class="fa fa-toggle-off">&nbsp;启用</i>
                    </el-button>
                    <el-button
                        size="mini"
                        type="danger"
                        title="删除"
                        :disabled="scope.row.name == 'admin'"
                        @click="userChange(0, scope)">
                        <i class="fa fa-user-times">&nbsp;删除</i>
                    </el-button>
                </template>
            </el-table-column>
            <el-table-column
                prop="id"
                sortable
                fixed
                width="100"
                label="用户ID">
            </el-table-column>
            <el-table-column
                prop="name"
                sortable
                width="130"
                label="姓名 / 账号">
            </el-table-column>
            <el-table-column
                prop="roles"
                sortable
                label="角色">
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
        <div class="pagination">
            {$page_html}
        </div>
    </el-card>

    <!--创建用户模态框-->
    <el-dialog
        title="创建用户"
        :visible.sync="newUserFlag"
        width="50%">
        <el-form ref="form" :model="form" label-width="100px" size="small">
            <el-form-item label="姓名 / 账号">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="密码">
                <el-input v-model="form.password"></el-input>
            </el-form-item>
            <el-form-item label="确认密码">
                <el-input v-model="form.rePassword"></el-input>
            </el-form-item>
            <el-form-item label="角色">
                <el-select
                    v-model="form.roles"
                    multiple
                    filterable
                    style="width:100%"
                    placeholder="请选择">
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
            <el-button size="mini" @click="newUserFlag = false">取消</el-button>
            <el-button size="mini" type="primary" @click="createUserNow">立即创建</el-button>
        </span>
    </el-dialog>

    <!--编辑用户模态框-->
    <el-dialog
        title="编辑用户"
        :visible.sync="editUserFlag"
        width="50%">
        <el-form ref="editForm" :model="editForm" label-width="100px" size="small">
            <el-form-item label="姓名 / 账号">
                <el-input v-model="editForm.name"></el-input>
            </el-form-item>
            <el-form-item label="修改密码">
                <el-input v-model="editForm.password" placeholder="默认不修改"></el-input>
            </el-form-item>
            <el-form-item label="角色">
                <el-select
                    v-model="editForm.roles"
                    multiple
                    filterable
                    style="width:100%"
                    placeholder="请选择">
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
            <el-button size="mini" @click="editUserFlag = false">取消</el-button>
            <el-button size="mini" type="primary" @click="saveUser">保存</el-button>
        </span>
    </el-dialog>
</div>

<script>

    let app = new Vue({

        el: '#app',

        data() {

            return {

                tableData: {$lists},
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

                rolesConfig: {$roles_config},
            }
        },

        methods: {

            //创建用户
            createUserNow() {

                if(!this.form.name) { this.$notify({title:'警告', message:'请输入姓名 / 账号', type:'warning' }); return false; }
                if(!this.form.password) { this.$notify({title:'警告', message:'请输入密码', type:'warning' }); return false; }
                if(!this.form.rePassword) { this.$notify({title:'警告', message:'请重新输入密码', type:'warning' }); return false; }
                if(this.form.password !== this.form.rePassword) { this.$notify({title:'警告', message:'两次密码输入不一致', type:'warning' }); return false; }
                if(this.form.roles.length === 0) { this.$notify({title:'警告', message:'请选择角色', type:'warning' }); return false; }

                this.$http.post(
                    '/admin/user?api=create_user',
                    this.form,
                    {emulateJSON:true}
                ).then(function(res){
                    if(res.body.code === 1) {
                        this.$notify({
                            title: '成功',
                            message: res.body.info,
                            type: 'success'
                        });
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }else {
                        this.$notify.error({message:res.body.info});
                    }
                },function(){
                    this.$notify.error({message: '服务器发生异常'});
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

                if(!this.editForm.name) { this.$notify({title:'警告', message:'请输入姓名 / 账号', type:'warning' }); return false; }
                if(this.editForm.roles.length === 0) { this.$notify({title:'警告', message:'请选择角色', type:'warning' }); return false; }

                this.$http.post(
                    '/admin/user?api=info_change',
                    {form:this.editForm},
                    {emulateJSON:true}
                ).then(function(res){
                    if(res.body.code === 1) {
                        this.$notify({
                            title: '成功',
                            message: res.body.info,
                            type: 'success'
                        });
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }else {
                        this.$notify.error({message:res.body.info});
                    }
                },function(){
                    this.$notify.error({message: '服务器发生异常'});
                });
            },

            //变更状态
            userChange(status, s) {

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

                this.$http.post(
                    '/admin/user?api=status_change',
                    {ids:ids, status:status},
                    {emulateJSON:true}
                ).then(function(res){
                    if(res.body.code === 1) {
                        this.$notify({
                            title: '成功',
                            message: res.body.info,
                            type: 'success'
                        });
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }else {
                        this.$notify.error({message:res.body.info});
                    }
                },function(){
                    this.$notify.error({message: '服务器发生异常'});
                });
            },

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
        },
    });
</script>
