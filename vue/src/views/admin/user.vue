<!--/user-->
<template>
    <div id="user">
        <vp-admin>
            <el-card class="box-card">
                <vp-table-search @reload="loadLists" @submit="search" :formFormat="formFormat" :pageLimit="true">
                    <template v-slot:btn>
                        <el-button @click="newUserFlag = !newUserFlag" type="primary" size="small"><i class="fa fa-user-plus">&nbsp;</i>{{$t('admin.user.add')}}</el-button>
                        <el-button @click="userChange(2)" change-type="0" type="warning" size="small"><i class="fa fa-toggle-on">&nbsp;</i>{{$t('admin.user.batch')}}{{$t('admin.user.stop')}}</el-button>
                        <el-button @click="userChange(1)" type="success" size="small"><i class="fa fa-toggle-off">&nbsp;</i>{{$t('admin.user.batch')}}{{$t('admin.user.open')}}</el-button>
                        <el-button @click="userChange(0)" type="danger" size="small"><i class="fa fa-user-times">&nbsp;</i>{{$t('admin.user.batch')}}{{$t('admin.user.del')}}</el-button>
                    </template>
                </vp-table-search>
                <el-table
                    :data="tableData"
                    stripe
                    size="small"
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
                        <template #default="scope">
                            <el-button
                                size="small"
                                type="primary"
                                :title="$t('admin.user.edit')"
                                @click="editUserNow(scope)">
                                <i class="fa fa-pencil">&nbsp;{{$t('admin.user.edit')}}</i>
                            </el-button>
                            <el-button
                                size="small"
                                type="warning"
                                :title="$t('admin.user.stop')"
                                v-if="scope.row.status == 1"
                                :disabled="scope.row.name == 'admin'"
                                @click="userChange(2, scope)">
                                <i class="fa fa-toggle-on">&nbsp;{{$t('admin.user.stop')}}</i>
                            </el-button>
                            <el-button
                                size="small"
                                type="success"
                                :title="$t('admin.user.open')"
                                v-if="scope.row.status == 2"
                                :disabled="scope.row.name == 'admin'"
                                @click="userChange(1, scope)">
                                <i class="fa fa-toggle-off">&nbsp;{{$t('admin.user.open')}}</i>
                            </el-button>
                            <el-button
                                size="small"
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
                        <template #default="scope">
                            <el-tag
                                v-for="role in scope.row.roles_mean"
                                :key="role"
                                size="small"
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

            <el-dialog
                :title="$t('admin.user.add')"
                v-model="newUserFlag"
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
                <template #footer>
                    <span class="dialog-footer">
                        <el-button size="small" @click="newUserFlag = false">{{$t('admin.user.cancel')}}</el-button>
                        <el-button size="small" type="primary" @click="createUserNow">{{$t('admin.user.save')}}</el-button>
                    </span>
                </template>
            </el-dialog>

            <el-dialog
                :title="$t('admin.user.edit')"
                v-model="editUserFlag"
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
                <template #footer>
                    <span class="dialog-footer">
                        <el-button size="small" @click="editUserFlag = false">{{$t('admin.user.cancel')}}</el-button>
                        <el-button size="small" type="primary" @click="saveUser">{{$t('admin.user.save')}}</el-button>
                    </span>
                </template>
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

                formFormat: [],
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
                    this.loadLists(
                        this.parseGET()['p']
                    );
                },
                deep: true,
            },

            tableData() {
                this.formFormat = [
                    { name:'ID', remoteName:'id', component:'input', componentType:'text' },
                    { name:this.$t('admin.user.account'), remoteName:'name', component:'input', componentType:'text' },
                    { name:this.$t('admin.user.role'), remoteName:'roles', component:'select', componentOptions:this.rolesConfig, componentMultiple:true },
                    { name:this.$t('admin.user.ctime'), remoteName:'ctime', component:'date-picker', componentType:'daterange' },
                    { name:this.$t('admin.user.utime'), remoteName:'utime', component:'date-picker', componentType:'daterange' },
                    { name:this.$t('admin.user.status'), remoteName:'status', component:'select', componentOptions:[{id:1,name:this.$t('admin.user.open')}, {id:2,name:this.$t('admin.user.stop')}, {id:0,name:this.$t('admin.user.del')}], componentMultiple:false },
                ];
            },
        },

        methods: {

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

            editUserNow(s) {
                this.editForm = s.row;
                this.editForm.password = '';
                this.editUserFlag = true;
            },

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

            tableStyle({row, column, rowIndex, columnIndex}) {
                if(column.label === this.$t('admin.user.status')) {
                    switch (row.status) {
                        case '0': { return { 'background':'#e4393c', 'color':'#fff!important' }; }
                        case '1': { return { 'background':'#009688', 'color':'#fff!important' }; }
                        case '2': { return { 'background':'#FFB800', 'color':'#fff!important' }; }
                    }
                }
            },

            handleSelectionChange(val) {
                this.tableSelection = val;
            },

            loadLists(page, query) {

                page = !page ? (page = 1) : page;
                query = !query ? (query = {}) : query;
                query = Object.assign(query, this.$route.query);

                this.poemRequest({
                    type: 'post',
                    url: '/admin/user?api=load&p=' + page,
                    data: query,
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

            search(query) {
                this.loadLists(this.parseGET()['p'], query);
            },
        },
    };
</script>

<style lang="less" scoped>

</style>
