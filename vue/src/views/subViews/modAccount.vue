<template>

    <el-dialog
        :title="$t('admin.userInfo.modAccountInfo')"
        v-model="dialogVisible"
        width="45%"
        @close="closeHandle"
        center>
        <el-form ref="form" :model="userInfo" label-width="140px" size="small">
            <el-form-item :label="$t('admin.userInfo.account')">
                <el-input v-model="userInfo.name"></el-input>
            </el-form-item>
            <el-form-item :label="$t('admin.userInfo.password')">
                <el-input v-model="userInfo.password" :placeholder="$t('admin.userInfo.modPassNull')"></el-input>
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
        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button size="small" @click="dialogVisible = false">{{$t('admin.public.cancel')}}</el-button>
                <el-button size="small" type="primary" @click="saveUserInfo">{{$t('admin.userInfo.save')}}</el-button>
            </span>
        </template>

    </el-dialog>
</template>

<script>

import helper from "../../mixins/helper";

export default {

    name: "modAccount",

    mixins: [helper],

    props: {
        value: {
            type: Boolean,
            default: false,
        },
        closeHandle: {
            type: Function,
            default: () => {},
        },
    },

    data() {
        return {
            userInfo: {},
            rolesConfig: {},
            dialogVisible: false,
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
                this.$notify({ message:this.$t('admin.public.isEmpty'), type:'warning'});
                return false;
            }

            this.poemRequest({
                type: 'post',
                url: '/admin/dash/userInfo?api=change_userinfo',
                data: this.userInfo,
                success: (res) => {
                    if(res.data.code === 1) {
                        this.dialogVisible = false;
                        this.$notify({ message:res.data.info, type:'success' });
                    }else {
                        this.$notify.error({message:res.data.info});
                    }
                },
            });
        },
    },

    watch: {

        value(v) {
            this.dialogVisible = v;
        },
    },
}
</script>

<style scoped lang="less">

</style>
