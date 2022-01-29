<!--用户信息-->
<include "../public/vue"/>

<style>
    .btn-group { text-align:center }
</style>

<div id="app">
    <el-card class="box-card">
        <el-form ref="form" :model="user_info" label-width="80px">
            <el-form-item label="账号">
                <el-input v-model="user_info.name"></el-input>
            </el-form-item>
            <el-form-item label="密码">
                <el-input v-model="user_info.password" placeholder="密码为空不修改"></el-input>
            </el-form-item>
            <el-form-item label="角色">
                <el-select
                    disabled
                    v-model="user_info.roles"
                    multiple
                    filterable
                    style="width:100%"
                    placeholder="请选择">
                    <el-option
                        v-for="item in rolesConfig"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                        {{item.name}}
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="账号状态">
                <el-input v-model="user_info.status" disabled></el-input>
            </el-form-item>
            <el-form-item label="注册时间">
                <el-input v-model="user_info.ctime" disabled></el-input>
            </el-form-item>
            <el-form-item label="更新时间">
                <el-input v-model="user_info.utime" disabled></el-input>
            </el-form-item>
            <div class="btn-group">
                <el-button @click="saveUserInfo" type="primary">保存修改</el-button>
            </div>
        </el-form>
    </el-card>
</div>

<script>

    let app = new Vue({

        el: '#app',

        data() {

            return {

                user_info: {$user_info},
                rolesConfig: {$roles_config},
            }
        },

        methods: {

            saveUserInfo() {

                if(!this.user_info.name) {
                    this.$notify({ message:'账号不能为空', type:'warning'});
                    return false;
                }

                this.$http.post(
                    '/admin/dash/user_info?api=change_userinfo',
                    {
                        name:this.user_info.name,
                        password:this.user_info.password ? this.user_info.password : '',
                    },
                    {emulateJSON:true}
                ).then(function(res){
                    if(res.body.code === 1) {
                        this.$notify({ message:res.body.info, type:'success' });
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }else {
                        this.$notify({ message:res.body.info, type:'warning'});
                    }
                },function(){
                    this.$notify.error({message: '服务器发生异常'});
                });
            },
        },
    });
</script>


