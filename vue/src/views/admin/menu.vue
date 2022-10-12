<!--/menu-->
<template>
    <div id="menu">
        <vp-admin>
            <el-card class="box-card">
                <el-button @click="newMenuFlag = !newMenuFlag" icon="fa fa-bars" type="primary" size="mini">&nbsp;{{$t('admin.menu.add')}}</el-button>
                <el-table
                    :data="tableData"
                    style="width: 100%;margin-bottom: 20px;"
                    row-key="id"
                    fit
                    :highlight-current-row="true"
                    stripe
                    :cell-style="tableStyle"
                    size="mini"
                    default-expand-all
                    :tree-props="{children: 'children', hasChildren: 'hasChildren'}">
                    <el-table-column
                        prop="title"
                        fixed
                        :label="$t('admin.menu.name')"
                        sortable
                        width="180">
                    </el-table-column>
                    <el-table-column
                        width="220"
                        fixed="left"
                        :label="$t('admin.menu.operation')">
                        <template slot-scope="scope">
                            <el-button
                                size="mini"
                                type="primary"
                                :title="$t('admin.menu.edit')"
                                @click="editMenuNow(scope)">
                                <i class="fa fa-pencil">&nbsp;{{$t('admin.menu.edit')}}</i>
                            </el-button>
                            <el-button
                                v-if="scope.row.status === '1'"
                                size="mini"
                                type="warning"
                                :title="$t('admin.menu.stop')"
                                :disabled="scope.row.lock === $t('admin.menu.locked')"
                                @click="menuChange(2, scope)">
                                <i class="fa fa-toggle-on">&nbsp;{{$t('admin.menu.stop')}}</i>
                            </el-button>
                            <el-button
                                v-if="scope.row.status === '2'"
                                size="mini"
                                type="success"
                                :title="$t('admin.menu.open')"
                                :disabled="scope.row.lock === $t('admin.menu.locked')"
                                @click="menuChange(1, scope)">
                                <i class="fa fa-user-times">&nbsp;{{$t('admin.menu.open')}}</i>
                            </el-button>
                            <el-button
                                size="mini"
                                type="danger"
                                :title="$t('admin.menu.del')"
                                :disabled="scope.row.lock === $t('admin.menu.locked')"
                                @click="menuChange(0, scope)">
                                <i class="fa fa-user-times">&nbsp;{{$t('admin.menu.del')}}</i>
                            </el-button>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="id"
                        label="ID"
                        sortable
                        width="80">
                    </el-table-column>
                    <el-table-column
                        prop="pid"
                        label="PID"
                        sortable
                        width="80">
                    </el-table-column>
                    <el-table-column
                        prop="icon"
                        :label="$t('admin.menu.icon')"
                        sortable
                        width="100">
                        <template slot-scope="scope">
                            <i v-if="scope.row.icon" :class="scope.row.icon"></i>
                            <i v-if="!scope.row.icon">-</i>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="href"
                        :label="$t('admin.menu.href')"
                        sortable
                        width="180">
                    </el-table-column>
                    <el-table-column
                        prop="sort"
                        :label="$t('admin.menu.sort')">
                    </el-table-column>
                    <el-table-column
                        prop="show"
                        :label="$t('admin.menu.display')">
                    </el-table-column>
                    <el-table-column
                        prop="lock"
                        :label="$t('admin.menu.lock')">
                    </el-table-column>
                    <el-table-column
                        prop="status_mean"
                        :label="$t('admin.menu.status')">
                    </el-table-column>
                    <el-table-column
                        prop="target"
                        :label="$t('admin.menu.target')">
                    </el-table-column>
                    <el-table-column
                        prop="ctime"
                        width="180"
                        :label="$t('admin.menu.ctime')">
                    </el-table-column>
                    <el-table-column
                        prop="utime"
                        width="160"
                        :label="$t('admin.menu.utime')">
                    </el-table-column>
                </el-table>
            </el-card>

            <!--添加-->
            <el-dialog
                :title="$t('admin.menu.add')"
                :visible.sync="newMenuFlag"
                width="50%">
                <el-form ref="form" :model="form" label-width="100px" size="small">
                    <el-form-item :label="$t('admin.menu.name')">
                        <el-input placeholder="请输入菜单名称" v-model="form.title"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.href')">
                        <el-select
                            v-model="form.href"
                            style="width:100%"
                            filterable
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option-group
                                v-for="(methods, func) in linkConfig"
                                :key="func"
                                :label="func + '控制器'">
                                <el-option
                                    v-for="(item, key) in methods"
                                    :key="key"
                                    :label="'【' + item.comment + '】  ' + item.link"
                                    :value="item.link">
                                </el-option>
                            </el-option-group>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.parent')">
                        <el-select
                            v-model="form.pid"
                            filterable
                            style="width:100%"
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option
                                v-for="(item, key) in menuConfig"
                                :key="key"
                                :label="item.title"
                                :disabled="form.id === item.id"
                                :value="item.id">
                                ID:{{item.id}} - PID:{{item.pid}} - {{item.title}}
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.icon')">
                        <el-select
                            v-model="form.icon"
                            filterable
                            clearable
                            style="width:100%"
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option
                                v-for="(item, key) in fontMap"
                                :key="key"
                                :label="item"
                                :value="item">
                                <template>
                                    <i :class="item"></i>&nbsp;&nbsp;{{item}}
                                </template>
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.display')">
                        <el-switch
                            v-model="form.show"
                            style="padding:6px 0 0 0; float:left;"
                            active-value="1"
                            inactive-value="0"
                            active-color="#13ce66"
                            inactive-color="#ff4949">
                        </el-switch>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.target')">
                        <el-select
                            v-model="form.target"
                            filterable
                            style="width:100%"
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option
                                v-for="(item, key) in openWayConfig"
                                :key="item"
                                :label="item"
                                :value="item">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.sort')">
                        <el-input
                            placeholder="请输入排序号码"
                            type="number"
                            minlength="0"
                            v-model="form.sort">
                        </el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button size="mini" @click="newMenuFlag = false">{{$t('admin.menu.cancel')}}</el-button>
                    <el-button size="mini" type="primary" @click="addMenu">{{$t('admin.menu.save')}}</el-button>
                </span>
            </el-dialog>

            <!--编辑-->
            <el-dialog
                :title="$t('admin.menu.edit')"
                :visible.sync="editMenuFlag"
                width="50%">
                <el-form ref="editForm" :model="editForm" label-width="100px" size="small">
                    <el-form-item :label="$t('admin.menu.name')">
                        <el-input :placeholder="$t('admin.public.pleaseInput')" v-model="editForm.title"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.href')">
                        <el-select
                            v-model="editForm.href"
                            style="width:100%"
                            filterable
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option-group
                                v-for="(methods, func) in linkConfig"
                                :key="func"
                                :label="func + 'controller'">
                                <el-option
                                    v-for="(item, key) in methods"
                                    :key="key"
                                    :label="'【' + item.comment + '】  ' + item.link"
                                    :value="item.link">
                                </el-option>
                            </el-option-group>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.parent')">
                        <el-select
                            v-model="editForm.pid"
                            filterable
                            style="width:100%"
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option
                                v-for="(item, key) in menuConfig"
                                :key="key"
                                :label="item.title"
                                :disabled="editForm.id === item.id"
                                :value="item.id">
                                ID:{{item.id}} - PID:{{item.pid}} - {{item.title}}
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.icon')">
                        <el-select
                            v-model="editForm.icon"
                            filterable
                            clearable
                            style="width:100%"
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option
                                v-for="(item, key) in fontMap"
                                :key="key"
                                :label="item"
                                :value="item">
                                <template>
                                    <i :class="item"></i>&nbsp;&nbsp;{{item}}
                                </template>
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.display')">
                        <el-switch
                            v-model="editForm.show"
                            style="padding:6px 0 0 0; float:left;"
                            active-value="1"
                            inactive-value="0"
                            active-color="#13ce66"
                            inactive-color="#ff4949">
                        </el-switch>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.target')">
                        <el-select
                            v-model="editForm.target"
                            filterable
                            style="width:100%"
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option
                                v-for="(item, key) in openWayConfig"
                                :key="item"
                                :label="item"
                                :value="item">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="$t('admin.menu.sort')">
                        <el-input
                            :placeholder="$t('admin.public.pleaseInput')"
                            type="number"
                            minlength="0"
                            v-model="editForm.sort">
                        </el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button size="mini" @click="editMenuFlag = false">{{$t('admin.menu.cancel')}}</el-button>
                    <el-button size="mini" type="primary" @click="saveMenu">{{$t('admin.menu.save')}}</el-button>
                </span>
            </el-dialog>
        </vp-admin>
    </div>
</template>

<script>

    import helper from "../../mixins/helper";

    export default {

        name: 'menuSys',

        mixins: [helper],

        data() {

            return {

                tableData: [],
                tableSelection: [],

                newMenuFlag: false,
                editMenuFlag: false,

                form: {
                    title: '',
                    href: '',
                    pid: '',
                    icon: '',
                    show: '1',
                    target: '',
                    sort: 0,
                },
                editForm: {},

                menuConfig: [],
                fontMap: [],
                linkConfig: [],
                openWayConfig: { 0:'_self', 1:'_blank',},
            }
        },

        created() {

            this.loadLists();
        },

        methods: {

            //加载列表
            loadLists() {
                let that = this;
                this.poemRequest({
                    type: 'post',
                    url: '/admin/menu?api=lists',
                    success: (res) => {
                        if(res.data.code === 1) {
                            that.tableData = res.data.data.lists;
                            that.menuConfig = res.data.data.menu_config;
                            that.fontMap = res.data.data.font_map;
                            that.linkConfig = res.data.data.link_config;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            //打开编辑
            editMenuNow(s) {

                this.editMenuFlag = true;

                this.poemRequest({
                    type: 'post',
                    url: '/admin/menu?api=load',
                    data: {id:s.row.id},
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.editForm = res.data.data;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            //添加菜单
            addMenu() {

                this.poemRequest({
                    type: 'post',
                    url: '/admin/menu?api=add',
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

            //保存菜单
            saveMenu() {

                this.poemRequest({
                    type: 'post',
                    url: '/admin/menu?api=save',
                    data: this.editForm,
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
            menuChange(status, s) {

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
                    url: '/admin/menu?api=status_change',
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
                if(column.label === this.$t('admin.menu.status')) {
                    if(row.status_mean === this.$t('admin.menu.open')) {
                        return 'background:#009688; color:#fff!important;';
                    }
                    if(row.status_mean === this.$t('admin.menu.stop')) {
                        return 'background:#FFB800; color:#fff!important;';
                    }
                }

                //锁
                if(column.label === this.$t('admin.menu.lock')) {
                    if(row.lock === this.$t('admin.menu.unlock')) {
                        return 'background:#009688; color:#fff!important;';
                    }
                    if(row.lock === this.$t('admin.menu.locked')) {
                        return 'background:#666; color:#fff!important;';
                    }
                }

                //显性
                if(column.label === this.$t('admin.menu.display')) {
                    if(row.show === this.$t('admin.menu.show')) {
                        return 'background:#009688; color:#fff!important;';
                    }
                    if(row.show === this.$t('admin.menu.hide')) {
                        return 'background:#666; color:#fff!important;';
                    }
                }
            },
        },
    };
</script>

<style lang="less" scoped>

    #menu {

        > .box-card {}
    }

    .el-table__row .cell {
        color:unset;
    }
</style>

