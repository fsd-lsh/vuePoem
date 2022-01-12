<template>
    <div id="sign-in">
        <div class="head">AdminPoem 后台登录</div>
        <el-form ref="form" :model="form" label-width="40px">
            <el-form-item label="账号">
                <el-input size="small" v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="密码">
                <el-input size="small" v-model="form.pwd" show-password></el-input>
            </el-form-item>
            <div class="button-group">
                <el-button @click="signIn" type="success" size="small">登录</el-button>
            </div>
        </el-form>
    </div>
</template>

<script>

export default {

    name: 'signIn',

    data() {
        return {
            form: {},
        }
    },

    created() {

        let sysBgCache = sessionStorage.getItem('layuiminiBgcolorId');
        if(!sysBgCache) {
            sessionStorage.setItem('layuiminiBgcolorId', 3);
        }
    },

    methods: {

        signIn() {

            if(!this.form.name) {
                this.$notify({ message:'账号不能为空', type:'warning'});
                return false;
            }
            if(!this.form.pwd) {
                this.$notify({ message:'密码不能为空', type:'warning'});
                return false;
            }

            this.$http.post(
                '/admin?api=sign_in',
                {
                    username: this.form.name,
                    password: this.form.pwd,
                },
                {emulateJSON:true}
            ).then(function(res){
                if(res.body.code === 1) {
                    this.$notify({ message:res.body.info, type:'success' });
                    setTimeout(function () {
                        window.location = res.body.data;
                    }, 1500);
                }else {
                    this.$notify({ message:res.body.info, type:'warning'});
                }
            },function(){
                this.$notify.error({message: '服务器发生异常'});
            });
        },
    }
}
</script>

<style lang="less">
    body {
        background: url('../../static/imgs/signin-bg.png') 0 0 / cover no-repeat;
        overflow: hidden;
    }
</style>

<style lang="less" scoped>

    #sign-in {

        margin: 15% auto;
        background: #fff;
        width: 420px;
        height: 266px;
        border-radius: 12px;

        .head {
            background-color: #48bfa4;
            height: 60px;
            line-height: 60px;
            border-radius: 12px 12px 0 0;
            font-size: 1.2rem;
            color: #fff;
            text-align: center;
        }

        #app {
            height: 100%;
            overflow: hidden;
        }
    }

    .el-form {

        padding:25px;

        .el-form-item {
            margin:0 0 10px 0;
        }

        .button-group {

            text-align:center;
            margin:20px 0 0 0;

            > button {
                width:100%;
                background:#48bfa4;
            }
        }
    }
</style>
