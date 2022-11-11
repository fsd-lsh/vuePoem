<!--/log/fwLog-->
<template>
    <div id="fw-log">
        <vp-admin>
            <el-card class="box-card">
                <el-table
                    :data="tableData"
                    stripe
                    size="small"
                    fit
                    :highlight-current-row="true"
                    style="width:100%">
                    <el-table-column
                        width="100"
                        fixed="left"
                        :label="$t('admin.fwLog.operation')">
                        <template #default="scope">
                        <el-button
                            size="small"
                            type="primary"
                            @click="showLogDetail(scope)">
                            <i class="fa fa-eye">&nbsp;{{$t('admin.fwLog.show')}}</i>
                        </el-button>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="log_name"
                        sortable
                        :label="$t('admin.fwLog.log')">
                    </el-table-column>
                    <el-table-column
                        prop="module_name"
                        width="400"
                        sortable
                        :label="$t('admin.fwLog.module')">
                    </el-table-column>
                    <el-table-column
                        prop="log_date"
                        sortable
                        :label="$t('admin.fwLog.date')">
                        <template #default="scope">
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
                :title="$t('admin.fwLog.logDetail') + ' - ' + (logDetailCount)"
                v-model="showLog"
                :highlight-current-row="true"
                top="5vh"
                width="90%">
                <el-table
                    id="table-show-log"
                    :data="logDetail"
                    size="small"
                    :max-height="400"
                    :row-class-name="logStatus"
                    :border="true"
                    style="width:100%">
                    <el-table-column
                        fixed="left"
                        width="100"
                        label="Num"
                        type="index">
                        <template #header>
                            <el-input
                                v-model="logSearch"
                                size="small"
                                :placeholder="$t('admin.public.filter')"/>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="log_time"
                        width="160"
                        fixed="left"
                        sortable
                        resizable
                        :label="$t('admin.fwLog.time')">
                    </el-table-column>
                    <el-table-column
                        prop="info"
                        width="3000"
                        resizable
                        :label="$t('admin.fwLog.detail')">
                    </el-table-column>
                </el-table>
                <template #footer>
                    <span class="dialog-footer">
                        <el-button size="small" @click="showLog = !showLog">{{$t('admin.fwLog.close')}}</el-button>
                    </span>
                </template>
            </el-dialog>
        </vp-admin>
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
                logDetailTemp: [],
                logDetailCount: 0,
                logSearch: '',
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
                            this.tableData = res.data.data;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            showLogDetail(v) {
                this.logDetail = [];
                this.logSearch = '';
                this.logDetailCount = 0;

                if(this.showLog) {
                    this.showLog = false;

                }else {
                    this.showLog = true;
                    this.openLog(v.row);
                }
            },

            logStatus({row, rowIndex}) {
                switch (row.level) {
                    case 0: { return 'fatal-row'; }
                    case 1: { return 'info-row'; }
                    default: { return ''; }
                }
            },

            openLog({module_name, log_name}) {
                this.poemRequest({
                    type: 'post',
                    url: '/admin/log/fwLog?api=open_log',
                    data: {
                        module_name: module_name,
                        log_name: log_name,
                    },
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.logDetail = res.data.data;
                            this.logDetailTemp = res.data.data;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            logSearchFunc(v) {
                if(v) {
                    this.logDetail = this.logDetail.filter(
                        data => !v ||
                            data.log_time.toLowerCase().includes(v.toLowerCase()) ||
                            data.info.toLowerCase().includes(v.toLowerCase())
                    );
                }else {
                    this.logDetail = this.logDetailTemp;
                }
            },
        },

        watch: {
            logDetail(v) {
                this.logDetailCount = v.length;
            },
            logSearch(v) {
                this.logSearchFunc(v);
            },
        },
    };
</script>

<style lang="less" scoped>

</style>
