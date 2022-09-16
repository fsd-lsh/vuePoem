<!--业务管理 - 面板统计-->

<template>

    <div id="dash">
        <poemAdmin>
            <el-card class="box-card">
                <el-row :gutter="20">
                    <el-col :span="8" class="dash-item">
                        <div>
                            <p><i class="fa fa-area-chart"></i>&nbsp;&nbsp;{{$t('admin.dash.dataStatistics')}}</p>
                            <el-row :gutter="10" class="dash-item-cover">

                                <el-col
                                    :span="12"
                                    class="dash-item-card"
                                    :key="flag"
                                    v-for="(subDash, flag) in dashData">
                                    <div>
                                        <p v-if="flag === 'user'">{{$t('admin.dash.user')}}</p>
                                        <p v-if="flag === 'roles'">{{$t('admin.dash.role')}}</p>
                                        <p v-if="flag === 'menu'">{{$t('admin.dash.menu')}}</p>
                                        <p v-if="flag === 'log/sysLog'">{{$t('admin.dash.log')}}</p>
                                        <div class="total-group">
                                            <span class="total">{{parseInt(subDash[0]) + parseInt(subDash[1]) + parseInt(subDash[2])}}</span>
                                            <span>(</span>
                                            <el-tooltip
                                                class="item"
                                                effect="dark"
                                                :key="status"
                                                v-for="(count, status) in subDash"
                                                :content="
                                                    (status === 1 && flag === 'user') ? $t('admin.dash.userNormal') :
                                                    (status === 2 && flag === 'user') ? $t('admin.dash.userStop') :
                                                    (status === 0 && flag === 'user') ? $t('admin.dash.userDel') :
                                                    (status === 1 && flag === 'roles') ? $t('admin.dash.roleNormal') :
                                                    (status === 2 && flag === 'roles') ? $t('admin.dash.roleStop') :
                                                    (status === 0 && flag === 'roles') ? $t('admin.dash.roleDel') :
                                                    (status === 1 && flag === 'menu') ? $t('admin.dash.menuNormal') :
                                                    (status === 2 && flag === 'menu') ? $t('admin.dash.menuStop') :
                                                    (status === 0 && flag === 'menu') ? $t('admin.dash.menuDel') :
                                                    (status === 1 && flag === 'log/sysLog') ? $t('admin.dash.logDanger') :
                                                    (status === 2 && flag === 'log/sysLog') ? $t('admin.dash.logWarning') :
                                                    (status === 0 && flag === 'log/sysLog') ? $t('admin.dash.logNormal') : ''
                                                "
                                                placement="top">
                                                <span
                                                    :class="
                                                        (flag !== 'log/sysLog') ? 'status_' + status :
                                                        (status === 1 && flag === 'log/sysLog') ? 'status_2' :
                                                        (status === 2 && flag === 'log/sysLog') ? 'status_0' :
                                                        (status === 0 && flag === 'log/sysLog') ? 'status_1' : ''
                                                    "
                                                    @click="goStatus(flag, status)">
                                                    {{count}}
                                                    <span v-if="status !== 2">&nbsp;&nbsp;</span>
                                                </span>
                                            </el-tooltip>
                                            <span>)</span>
                                        </div>
                                    </div>
                                </el-col>
                            </el-row>
                        </div>
                    </el-col>
                </el-row>
            </el-card>
        </poemAdmin>
    </div>
</template>

<script>

    import helper from "../../mixins/helper";

    export default {

        name: 'dash',

        mixins: [helper],

        data() {

            return {
                dashData: [],
                dashKeys: [],
            }
        },

        created() {

            //加载面板数据
            this.loadDash();
        },

        methods: {

            //加载面板数据
            loadDash() {
                this.poemRequest({
                    type: 'post',
                    url: '/admin/dash?api=load',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.dashData = res.data.data;
                            this.dashKeys = Object.keys(this.dashData);
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            //跳转统计状态
            goStatus(flag, status) {

                if(this.dashKeys.length === 0) {
                    return false;
                }

                if(!this.in_array(flag, this.dashKeys)) {
                    return false;
                }
                this.$router.push({
                    path: flag,
                    query: {
                        status: status,
                    }
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

