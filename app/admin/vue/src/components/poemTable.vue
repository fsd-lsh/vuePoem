<template>

    <div id="poem-table">

        <!--table-->
        <el-card class="box-card">
            <el-button @click="openDiaLog('new')" icon="fa fa-user-plus" type="primary" size="small">&nbsp;{{$t('admin.user.add')}}</el-button>
            <el-button @click="statusChange(2)" change-type="0" icon="fa fa-toggle-on" type="warning" size="small">&nbsp;{{$t('admin.user.batch')}}{{$t('admin.user.stop')}}</el-button>
            <el-button @click="statusChange(1)" icon="fa fa-toggle-off" type="success" size="small">&nbsp;{{$t('admin.user.batch')}}{{$t('admin.user.open')}}</el-button>
            <el-button @click="statusChange(0)" icon="fa fa-user-times" type="danger" size="small">&nbsp;{{$t('admin.user.batch')}}{{$t('admin.user.del')}}</el-button>
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
                            @click="openDiaLog(scope)">
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
                    v-for="(item, key) in column"
                    :prop="item"
                    sortable
                    fixed
                    :key="key"
                    :label="$t('admin.field.'+item)">
                </el-table-column>
            </el-table>
            <div class="pagination" v-html="pageHtml"></div>
        </el-card>

        <!--模态框-->
        <el-dialog
            v-if="dialogConfig.length !== 0"
            :title="diaLogTitle"
            :visible.sync="diaLogFlag"
            width="40%">
            <el-form ref="form" label-width="90px" size="small">

                <div v-for="(item, key) in dialogConfig">

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
                            placeholder="请选择">
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
            <span slot="footer" class="dialog-footer">
            <el-button size="mini" @click="diaLogFlag = false">{{$t('admin.user.cancel')}}</el-button>
            <el-button size="mini" type="primary" @click="saveDiaLog">{{$t('admin.user.save')}}</el-button>
        </span>
        </el-dialog>
    </div>
</template>

<script>
import helper from "../mixins/helper";
export default {

    name: "poemTable",

    mixins: [
        helper
    ],

    props: {
        url: { type:String, default:'', },
        dialog: { type:Array, default:[], },
    },

    data() {

        return {

            api: '',
            tableSelection: [],
            tableData: [],
            pageHtml: '',
            column: [],
            diaLogFlag: false,
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
                        this.tableData = res.data.data.lists;
                        this.pageHtml = res.data.data.page_html;
                        this.column = res.data.data.column;
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

        //模态框打开
        openDiaLog(scope) {

            this.diaLogFlag = true;

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
                        this.diaLogFlag = false;
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


    /deep/.el-select {
        width: 100%;
    }
}
</style>
