<!--业务管理 - 面板统计-->

<template>

    <div id="dash">
        <el-card class="box-card">
            <el-row :gutter="20">
                <el-col :span="8" class="dash-item">
                    <div>
                        <p><i class="fa fa-area-chart"></i>&nbsp;&nbsp;{{$t('admin.dash.dataStatistics')}}</p>
                        <el-row :gutter="10" class="dash-item-cover">
                            <el-col :span="12" class="dash-item-card">
                                <div>
                                    <p>{{$t('admin.dash.user')}}</p>
                                    <div class="total-group">
                                        <span class="total">{{parseInt(adminTotal[0]) + parseInt(adminTotal[1]) + parseInt(adminTotal[2])}}</span>
                                        <span>(</span>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.userNormal')" placement="top">
                                            <span class="status_1">{{adminTotal[1]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.userStop')" placement="top">
                                            <span class="status_2">{{adminTotal[2]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.userDel')" placement="top">
                                            <span class="status_0">{{adminTotal[0]}}</span>
                                        </el-tooltip>
                                        <span>)</span>
                                    </div>
                                </div>
                            </el-col>
                            <el-col :span="12" class="dash-item-card">
                                <div>
                                    <p>{{$t('admin.dash.role')}}</p>
                                    <div class="total-group">
                                        <span class="total">{{parseInt(roleTotal[0]) + parseInt(roleTotal[1]) + parseInt(roleTotal[2])}}</span>
                                        <span>(</span>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.roleNormal')" placement="top">
                                            <span class="status_1">{{roleTotal[1]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.roleStop')" placement="top">
                                            <span class="status_2">{{roleTotal[2]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.roleDel')" placement="top">
                                            <span class="status_0">{{roleTotal[0]}}</span>
                                        </el-tooltip>
                                        <span>)</span>
                                    </div>
                                </div>
                            </el-col>
                            <el-col :span="12" class="dash-item-card">
                                <div>
                                    <p>{{$t('admin.dash.menu')}}</p>
                                    <div class="total-group">
                                        <span class="total">{{parseInt(menuTotal[0]) + parseInt(menuTotal[1]) + parseInt(menuTotal[2])}}</span>
                                        <span>(</span>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.menuNormal')" placement="top">
                                            <span class="status_1">{{menuTotal[1]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.menuStop')" placement="top">
                                            <span class="status_2">{{menuTotal[2]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.menuDel')" placement="top">
                                            <span class="status_0">{{menuTotal[0]}}</span>
                                        </el-tooltip>
                                        <span>)</span>
                                    </div>
                                </div>
                            </el-col>
                            <el-col :span="12" class="dash-item-card">
                                <div>
                                    <p>{{$t('admin.dash.log')}}</p>
                                    <div class="total-group">
                                        <span class="total">{{parseInt(logTotal[0]) + parseInt(logTotal[1]) + parseInt(logTotal[2])}}</span>
                                        <span>(</span>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.logNormal')" placement="top">
                                            <span class="status_1">{{logTotal[0]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.logWarning')" placement="top">
                                            <span class="status_2">{{logTotal[1]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" :content="$t('admin.dash.logDanger')" placement="top">
                                            <span class="status_0">{{logTotal[2]}}</span>
                                        </el-tooltip>
                                        <span>)</span>
                                    </div>
                                </div>
                            </el-col>
                        </el-row>
                    </div>
                </el-col>
            </el-row>

            <div id="main" style="width: 600px;height:400px; display:none"></div>
        </el-card>
    </div>
</template>

<script>

    import helper from "../../mixins/helper";

    export default {

        name: 'dash',

        mixins: [helper],

        data() {

            return {

                adminTotal: [],
                roleTotal: [],
                menuTotal: [],
                logTotal: [],
            }
        },

        created() {

            //加载面板数据
            this.loadDash();
        },

        mounted() {

        },

        methods: {

            //加载面板数据
            loadDash() {
                this.poemRequest({
                    type: 'post',
                    url: '/admin/dash/main?api=load',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.adminTotal = res.data.data.admin_total;
                            this.roleTotal = res.data.data.role_total;
                            this.menuTotal = res.data.data.menu_total;
                            this.logTotal = res.data.data.log_total;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },
        },
    }
</script>

<style lang="less" scoped>

    .dash-item {

        margin: 0 0 10px 0;

        > div {

            border: 1px solid #f2f2f2;
            border-radius: 5px;

            > p {
                padding: 14px 10px;
                border-bottom: 1px solid #f2f2f2;
                margin:0;
            }

            .dash-item-cover {

                padding: 10px;

                .dash-item-card {

                    > div {

                        padding: 5px 15px;
                        border-radius: 5px;
                        margin: 0 0 10px 0;
                        background-color: #F8F8F8;

                        > p {
                            font-size: 1rem;
                        }

                        > .total-group {

                            color: #666;

                            .total {
                                font-weight: 600;
                                font-size: 1.2rem;
                            }
                        }
                    }
                }
            }
        }
    }

    .status_0, .status_1, .status_2 { font-weight:600; cursor:pointer; }
    .status_0 { color:#e4393c; }
    .status_1 { color:#009688; }
    .status_2 { color:#FFB800; }
</style>

