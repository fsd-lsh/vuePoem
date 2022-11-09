<template>

    <div id="poem-table">

        <!--table-->
        <el-card class="box-card">
            <el-button @click="openSaveDiaLog('new')" type="primary" size="mini" v-if="this.dialog.length > 0"><i class="fa fa-user-plus">&nbsp;</i>{{$t('admin.user.add')}}</el-button>
            <el-button @click="statusChange(2)" change-type="0" type="warning" size="mini"><i class="fa fa-toggle-on">&nbsp;</i>{{$t('admin.user.batch')}}{{$t('admin.user.stop')}}</el-button>
            <el-button @click="statusChange(1)" type="success" size="mini"><i class="fa fa-toggle-off">&nbsp;</i>{{$t('admin.user.batch')}}{{$t('admin.user.open')}}</el-button>
            <el-button @click="statusChange(0)" type="danger" size="mini"><i class="fa fa-user-times">&nbsp;</i>{{$t('admin.user.batch')}}{{$t('admin.user.del')}}</el-button>
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
                    min-width="220"
                    fixed="left"
                    :label="$t('admin.user.operation')">
                    <template #default="scope">
                        <el-button
                            v-if="scope.row.viewShow === 1"
                            size="mini"
                            type="info"
                            :title="$t('admin.public.view')"
                            @click="openViewDialog(scope)">
                            <i class="fa fa-eye">{{$t('admin.public.view')}}</i>
                        </el-button>
                        <el-button
                            v-if="scope.row.editShow === 1"
                            size="mini"
                            type="primary"
                            :title="$t('admin.user.edit')"
                            @click="openSaveDiaLog(scope)">
                            <i class="fa fa-pencil">&nbsp;{{$t('admin.user.edit')}}</i>
                        </el-button>
                        <el-button
                            size="mini"
                            type="warning"
                            :title="$t('admin.user.stop')"
                            v-if="scope.row.status == 1"
                            :disabled="scope.row.name == 'admin'"
                            @click="statusChange(2, scope)">
                            <i class="fa fa-toggle-on">&nbsp;{{$t('admin.user.stop')}}</i>
                        </el-button>
                        <el-button
                            size="mini"
                            type="success"
                            :title="$t('admin.user.open')"
                            v-if="scope.row.status == 2"
                            :disabled="scope.row.name == 'admin'"
                            @click="statusChange(1, scope)">
                            <i class="fa fa-toggle-off">&nbsp;{{$t('admin.user.open')}}</i>
                        </el-button>
                        <el-button
                            size="mini"
                            type="danger"
                            :title="$t('admin.user.del')"
                            :disabled="scope.row.name == 'admin'"
                            @click="statusChange(0, scope)">
                            <i class="fa fa-user-times">&nbsp;{{$t('admin.user.del')}}</i>
                        </el-button>
                    </template>
                </el-table-column>

                <el-table-column
                    :width="(item === 'id' || item === 'status' ) ? 90 : 180"
                    :fixed="(item === 'id')"
                    v-for="(item, key) in column"
                    :prop="item"
                    sortable
                    :key="key"
                    :label="$t('admin.field.'+item)">
                </el-table-column>
            </el-table>
            <div class="pagination" v-html="pageHtml"></div>
        </el-card>

        <!--模态框（添加、编辑）-->
        <el-dialog
            v-if="dialogConfig.length !== 0"
            :title="diaLogTitle"
            v-model="diaLogSaveFlag"
            width="40%">
            <el-form ref="form" label-width="90px" size="small">

                <div v-for="(item, key) in dialogConfig" :key="key">

                    <el-form-item
                        v-if="item.el === 'input'"
                        :label="$t('admin.field.'+item.name)">
                        <el-input
                            :type="item.type"
                            :placeholder="item.placeholder"
                            v-model="item.value"
                            :minlength="item.minlength"
                            :maxlength="item.maxLength"
                            show-word-limit/>
                    </el-form-item>

                    <el-form-item
                        v-if="item.el === 'select'"
                        :label="$t('admin.field.'+item.name)">
                        <el-select
                            v-model="item.value"
                            :placeholder="$t('admin.public.pleaseInput')">
                            <el-option
                                v-for="s_item in item.options"
                                :key="s_item.value"
                                :label="s_item.label"
                                :value="s_item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </div>
            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-button size="mini" @click="diaLogSaveFlag = false">{{$t('admin.user.cancel')}}</el-button>
                    <el-button size="mini" type="primary" @click="saveDiaLog">{{$t('admin.user.save')}}</el-button>
                </span>
            </template>
        </el-dialog>

        <!--模态框（查看）-->
        <el-dialog
            v-if="rowView.viewEnable"
            v-model="diaLogViewFlag"
            width="70%">
            <template>
                <el-descriptions class="margin-top" :title="$t('admin.public.view')" :column="2" size="small" border>
                    <el-descriptions-item
                        :key="key"
                        v-for="(item, key) in this.column">
                        <template #label>
                            <i :class="{
                                'fa fa-spinner': (item === 'status'),
                                'fa fa-clock-o': (item === 'ctime' || item === 'utime'),
                                'fa fa-circle-o': (item !== 'status' && item !== 'ctime' && item !== 'utime'),
                            }"/>
                            <span>{{$t('admin.field.'+item)}}</span>
                        </template>
                        {{diaLogViewForm[item]}}
                    </el-descriptions-item>
                </el-descriptions>
                <slot name="diaLogViewSlot"></slot>
            </template>
            <template #footer>
                <span class="dialog-footer">
                    <el-button size="mini" @click="diaLogViewFlag = false">{{$t('admin.user.cancel')}}</el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>

<script>
import helper from "../mixins/helper";
export default {

    name: "vpTable",

    mixins: [
        helper
    ],

    props: {
        url: {
            type: String,
            default: '',
        },
        dialog: {
            type: Array,
            default: [],
        },
        rowView: {
            type: Object,
            default: () => {
                return {
                    viewEnable: false,
                    viewCustom: false,
                }
            },
        },
    },

    data() {

        return {

            api: '',
            tableSelection: [],
            tableData: [],
            pageHtml: '',
            column: [],
            diaLogSaveFlag: false,
            diaLogViewFlag: false,
            diaLogViewForm: {},
            diaLogTitle: '',
            dialogConfig: [],
        }
    },

    created() {

        //加载列表
        this.loadList(
            this.parseGET()['p']
        );
    },

    mounted() {

    },

    methods: {

        //加载列表
        loadList(page) {

            page = (!page) ? (page = 1) : (page);

            this.poemRequest({
                url: this.url + '?p='+page + '&api=lists',
                type: 'post',
                data: [],
                success: (res) => {
                    if(res.data.code === 1) {

                        this.pageHtml = res.data.data.page_html;
                        this.column = res.data.data.column;
                        this.tableData = res.data.data.lists;

                        if(this.tableData.length) {
                            for (let key = 0; key < this.tableData.length; key++) {
                                this.tableData[key].editShow = (this.dialog.length > 0) ? 1 : 0;    //行编辑按钮显性
                                this.tableData[key].viewShow = (this.rowView.viewEnable) ? 1 : 0;   //行查看按钮显性
                            }
                        }
                    }else {
                        this.$notify.error({message:res.data.info});
                    }
                },
            });
        },

        //变更状态
        statusChange(status, s) {

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
                url: this.url + '?api=status',
                data: {ids:ids, status:status},
                success: (res) => {
                    if(res.data.code === 1) {
                        this.$notify({
                            title: '成功',
                            message: res.data.info,
                            type: 'success'
                        });
                        this.loadList(1);
                    }else {
                        this.$notify.error({message:res.data.info});
                    }
                },
            });
        },

        //初始化dialog
        initDialog() {

            if(this.dialog.length) {
                this.dialogConfig = JSON.parse(JSON.stringify(this.dialog));
            }
        },

        //列表行颜色切换
        tableStyle({row, column, rowIndex, columnIndex}) {

            if(column.label === this.$t('admin.field.status')) {

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

        //保存模态框打开
        openSaveDiaLog(scope) {

            this.diaLogSaveFlag = true;

            //Init
            this.initDialog();

            //编辑
            if(scope !== 'new') {
                this.diaLogTitle = this.$t('admin.public.edit');
                for (let key = 0; key < this.dialogConfig.length; key++) {
                    if(scope.row[this.dialogConfig[key].name]) {
                        this.dialogConfig[key].value = scope.row[this.dialogConfig[key].name];
                    }
                    if(scope.row['id']) {
                        this.dialogConfig[key].id = scope.row.id;
                    }
                }
            }

            //添加
            if(scope === 'new') {
                this.diaLogTitle = this.$t('admin.public.add');
            }
        },

        //查看模态框打开
        openViewDialog(scope) {

            this.diaLogViewForm = scope.row;
            this.diaLogViewFlag = true;
            this.$emit('openViewDialog', scope.row);
        },

        //模态框保存
        saveDiaLog() {

            //表单转换
            let form = {};
            for (let key = 0; key < this.dialogConfig.length; key++) {
                form[this.dialogConfig[key].name] = this.dialogConfig[key].value;
                form['id'] = this.dialogConfig[key].id;
            }

            this.poemRequest({
                type: 'post',
                url: this.url + '?api=save',
                data: form,
                success: (res) => {
                    if(res.data.code === 1) {
                        this.$notify({
                            title: '成功',
                            message: res.data.info,
                            type: 'success'
                        });
                        this.diaLogSaveFlag = false;
                        if(!form['id']) {
                            this.loadList(1);
                        }else {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000)
                        }
                    }else {
                        this.$notify.error({message:res.data.info});
                    }
                },
            });
        },
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
}
</script>

<style lang="less" scoped>

#poem-table {


    :deep(.el-select) {
        width: 100%;
    }
}
</style>
