<!--系统管理 - 权限设置-->

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
                    label="操作">
                    <template slot-scope="scope">
                        <el-button
                            size="mini"
                            type="primary"
                            @click="showLogDetail(scope)">
                            <i class="fa fa-eye">&nbsp;查看</i>
                        </el-button>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="module_path"
                    width="400"
                    sortable
                    label="模块">
                </el-table-column>
                <el-table-column
                    prop="log_name"
                    sortable
                    label="日志">
                </el-table-column>
                <el-table-column
                    prop="log_date"
                    sortable
                    label="日期">
                    <template slot-scope="scope">
                        <i class="el-icon-time"></i>
                        <span style="margin-left: 10px">{{ scope.row.log_date }}</span>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="log_hour"
                    sortable
                    label="小时">
                </el-table-column>
            </el-table>
        </el-card>

        <el-dialog
            title="日志详情"
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
                    label="序号">
                </el-table-column>
                <el-table-column
                    prop="info"
                    width="3000"
                    label="详情">
                </el-table-column>
            </el-table>
            <span slot="footer" class="dialog-footer">
        <el-button size="mini" @click="showLog = !showLog">关闭</el-button>
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
                    url: '/admin/log/fw_log?api=load',
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

<style lang="less">
    @import "../../../static/css/public";
</style>
