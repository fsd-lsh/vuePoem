<!--业务管理 - 面板统计-->

<template>

    <div id="dash">
        <el-card class="box-card">
            <el-row :gutter="20">
                <el-col :span="8" class="dash-item">
                    <div>
                        <p><i class="fa fa-area-chart"></i>&nbsp;&nbsp;数据统计</p>
                        <el-row :gutter="10" class="dash-item-cover">
                            <el-col :span="12" class="dash-item-card">
                                <div>
                                    <p>用户</p>
                                    <div class="total-group">
                                        <span class="total">{{parseInt(adminTotal[0]) + parseInt(adminTotal[1]) + parseInt(adminTotal[2])}}</span>
                                        <span>（</span>
                                        <el-tooltip class="item" effect="dark" content="正常用户" placement="top">
                                            <span class="status_1">{{adminTotal[1]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" content="冻结用户" placement="top">
                                            <span class="status_2">{{adminTotal[2]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" content="删除用户" placement="top">
                                            <span class="status_0">{{adminTotal[0]}}</span>
                                        </el-tooltip>
                                        <span>）</span>
                                    </div>
                                </div>
                            </el-col>
                            <el-col :span="12" class="dash-item-card">
                                <div>
                                    <p>角色</p>
                                    <div class="total-group">
                                        <span class="total">{{parseInt(roleTotal[0]) + parseInt(roleTotal[1]) + parseInt(roleTotal[2])}}</span>
                                        <span>（</span>
                                        <el-tooltip class="item" effect="dark" content="正常角色" placement="top">
                                            <span class="status_1">{{roleTotal[1]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" content="暂停角色" placement="top">
                                            <span class="status_2">{{roleTotal[2]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" content="删除角色" placement="top">
                                            <span class="status_0">{{roleTotal[0]}}</span>
                                        </el-tooltip>
                                        <span>）</span>
                                    </div>
                                </div>
                            </el-col>
                            <el-col :span="12" class="dash-item-card">
                                <div>
                                    <p>菜单</p>
                                    <div class="total-group">
                                        <span class="total">{{parseInt(menuTotal[0]) + parseInt(menuTotal[1]) + parseInt(menuTotal[2])}}</span>
                                        <span>（</span>
                                        <el-tooltip class="item" effect="dark" content="启用菜单" placement="top">
                                            <span class="status_1">{{menuTotal[1]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" content="停用菜单" placement="top">
                                            <span class="status_2">{{menuTotal[2]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" content="删除菜单" placement="top">
                                            <span class="status_0">{{menuTotal[0]}}</span>
                                        </el-tooltip>
                                        <span>）</span>
                                    </div>
                                </div>
                            </el-col>
                            <el-col :span="12" class="dash-item-card">
                                <div>
                                    <p>日志</p>
                                    <div class="total-group">
                                        <span class="total">{{parseInt(logTotal[0]) + parseInt(logTotal[1]) + parseInt(logTotal[2])}}</span>
                                        <span>（</span>
                                        <el-tooltip class="item" effect="dark" content="普通日志" placement="top">
                                            <span class="status_1">{{logTotal[0]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" content="警告日志" placement="top">
                                            <span class="status_2">{{logTotal[1]}}&nbsp;&nbsp;</span>
                                        </el-tooltip>
                                        <el-tooltip class="item" effect="dark" content="高危日志" placement="top">
                                            <span class="status_0">{{logTotal[2]}}</span>
                                        </el-tooltip>
                                        <span>）</span>
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
    export default {

        name: 'dash',

        data() {

            return {

                adminTotal: [1,2,3],
                roleTotal: [1,2,3],
                menuTotal: [1,2,3],
                logTotal: [1,2,3],
            }
        },

        created() {

        },

        mounted() {

        },

        methods: {

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

