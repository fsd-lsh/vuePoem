<!--系统管理 - 系统监控-->

<template>

    <div id="system">

        <vp-admin>
            <el-row :gutter="20" class="sta">

                <el-col :span="12" class="sta-item">
                    <el-card class="box-card">
                        <p>{{$t('admin.system.info')}}</p>
                        <ul>
                            <li v-for="(item, key) in systemInfo.sys">
                                {{key}}: {{item}}
                            </li>
                        </ul>
                    </el-card>
                </el-col>

                <el-col :span="12" class="sta-item">
                    <el-card class="box-card">
                        <p>CPU</p>
                        <ul>
                            <li v-for="(item, key) in systemInfo.cpu">
                                {{item}}
                            </li>
                        </ul>
                    </el-card>
                </el-col>

                <el-col :span="12" class="sta-item">
                    <el-card class="box-card">
                        <p>{{$t('admin.system.memory')}}</p>
                        <ul>
                            <li v-for="(item, key) in systemInfo.ram">
                                {{item}}
                            </li>
                        </ul>
                    </el-card>
                </el-col>

                <el-col :span="12" class="sta-item">
                    <el-card class="box-card">
                        <p>{{$t('admin.system.disk')}}</p>
                        <ul>
                            <li v-for="(item, key) in systemInfo.hdd">
                                {{item}}
                            </li>
                        </ul>
                    </el-card>
                </el-col>
            </el-row>
        </vp-admin>
    </div>
</template>

<script>

    import helper from "../../mixins/helper";

    export default {

        name: 'system',

        mixins: [helper],

        data() {

            return {

                timer: '',
                systemInfo: {
                    cpu: [],
                    hdd: [],
                    ram: [],
                    sys: {},
                },
            }
        },

        created() {

            //加载系统监控信息
            let that = this;
            this.timer = setInterval(() => {
                that.loadSystemInfo();
            }, 2500);
        },

        methods: {

            //加载系统监控信息
            loadSystemInfo() {
                this.poemRequest({
                    type: 'post',
                    url: '/admin/system?api=load',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.systemInfo = res.data.data;
                        }
                    },
                });
            },
        },

        destroyed() {
            clearInterval(this.timer);
        },
    };
</script>

<style lang="less" scoped>

    #system {

        .sta {

            .sta-item {

                margin:20px 0 0 0;

                .box-card {

                    height:280px;
                    max-height:280px;
                    overflow-y:scroll;

                    p:nth-child(1) {
                        font-size:1rem;
                        font-weight:600;
                    }
                }
            }
        }
    }
</style>

