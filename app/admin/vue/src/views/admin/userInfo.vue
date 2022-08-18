<!--用户信息-->

<template>
    <div id="user-info">

        <poemAdmin>
            <el-card class="box-card">
                <el-form ref="form" :model="userInfo" label-width="180px">
                    <el-form-item :label="$t('admin.userInfo.account')">
                        <el-input v-model="userInfo.name"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.userInfo.password')">
                        <el-input v-model="userInfo.password" placeholder="密码为空不修改"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.userInfo.roles')">
                        <el-select
                            disabled
                            v-model="userInfo.roles"
                            multiple
                            filterable
                            style="width:100%"
                            :placeholder="$t('admin.public.selectMenu')">
                            <el-option
                                v-for="item in rolesConfig"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                                {{item.name}}
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="$t('admin.userInfo.accountStatus')">
                        <el-input v-model="userInfo.status" disabled></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.userInfo.stime')">
                        <el-input v-model="userInfo.ctime" disabled></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('admin.userInfo.utime')">
                        <el-input v-model="userInfo.utime" disabled></el-input>
                    </el-form-item>
                    <div class="btn-group">
                        <el-button @click="saveUserInfo" type="primary">{{$t('admin.userInfo.save')}}</el-button>
                    </div>
                </el-form>
            </el-card>
        </poemAdmin>
    </div>
</template>

<script>

    import helper from "../../mixins/helper";

    export default {

        name: 'userInfo',

        mixins: [helper],

        data() {

            return {

                userInfo: {},
                rolesConfig: {},
            }
        },

        created() {

            this.loadUserInfo();
        },

        methods: {

            loadUserInfo() {

                this.poemRequest({
                    type: 'post',
                    url: '/admin/dash/userInfo?api=load',
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.userInfo = res.data.data.user_info;
                            this.rolesConfig = res.data.data.roles_config;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            saveUserInfo() {

                if(!this.userInfo.name) {
                    this.$notify({ message:'账号不能为空', type:'warning'});
                    return false;
                }

                this.poemRequest({
                    type: 'post',
                    url: '/admin/dash/userInfo?api=change_userinfo',
                    data: this.userInfo,
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.$notify({ message:res.data.info, type:'success' });
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },
        },
    };
</script>

<style lang="less" scoped>
    .btn-group { text-align:center }
</style>
