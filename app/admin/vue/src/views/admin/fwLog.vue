<!--系统管理 - 框架日志-->

<template>
    <div id="fw-log">

        <el-card class="box-card">
            <el-table
                :data="tableData"
                stripe
                size="mini"
                fit
                :highlight-current-row="true"
                style="width:100%">
                <el-table-column
                    width="100"
                    fixed="left"
                    :label="$t('admin.fwLog.operation')">
                    <template slot-scope="scope">
                        <el-button
                            size="mini"
                            type="primary"
                            @click="showLogDetail(scope)">
                            <i class="fa fa-eye">&nbsp;{{$t('admin.fwLog.show')}}</i>
                        </el-button>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="module_path"
                    width="400"
                    sortable
                    :label="$t('admin.fwLog.module')">
                </el-table-column>
                <el-table-column
                    prop="log_name"
                    sortable
                    :label="$t('admin.fwLog.log')">
                </el-table-column>
                <el-table-column
                    prop="log_date"
                    sortable
                    :label="$t('admin.fwLog.date')">
                    <template slot-scope="scope">
                        <i class="el-icon-time"></i>
                        <span style="margin-left: 10px">{{ scope.row.log_date }}</span>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="log_hour"
                    sortable
                    :label="$t('admin.fwLog.hour')">
                </el-table-column>
            </el-table>
        </el-card>

        <el-dialog
            :title="$t('admin.fwLog.logDetail')"
            :visible.sync="showLog"
            top="5vh"
            width="90%">
            <el-table
                id="table-show-log"
                :data="logDetail"
                size="mini"
                :max-height="400"
                :row-class-name="logStatus"
                style="width:100%">
                <el-table-column
                    prop="key"
                    width="60"
                    fixed="left"
                    :label="$t('admin.fwLog.num')">
                </el-table-column>
                <el-table-column
                    prop="info"
                    width="3000"
                    :label="$t('admin.fwLog.detail')">
                </el-table-column>
            </el-table>
            <span slot="footer" class="dialog-footer">
        <el-button size="mini" @click="showLog = !showLog">{{$t('admin.fwLog.close')}}</el-button>
      </span>
        </el-dialog>
    </div>
</template>

<script>

    import helper from "../../mixins/helper";

    export default {

        name: 'fwLog',

        mixins: [helper],

        data() {

            return {

                tableData: [],
                showLog: false,
                logDetail: [],
            }
        },

        created() {

            this.loadList();
        },

        methods: {

            loadList() {

                this.poemRequest({
                    type: 'post',
                    url: '/admin/log/fwLog?api=load',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.tableData = res.data.data.lists;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            showLogDetail(v) {

                this.logDetail = v.row.log_detail;

                if(this.showLog) {
                    this.showLog = false;

                }else {
                    this.showLog = true;
                }
            },

            logStatus({row, rowIndex}) {

                switch (row.level) {
                    case 'fatal': { return 'fatal-row'; }
                    case 'info': { return 'info-row'; }
                    default: { return ''; }
                }
            },
        },
    };
</script>

<style lang="less" scoped>

</style>
