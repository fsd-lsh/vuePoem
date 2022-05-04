<template>
    <div id="sign-in">
        <div class="head">{{$t('admin.signIn.title')}}</div>
        <el-form ref="form" :model="form" label-width="65px">
            <el-form-item :label="$t('admin.signIn.account')">
                <el-input size="small" v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item :label="$t('admin.signIn.password')">
                <el-input size="small" v-model="form.pwd" show-password></el-input>
            </el-form-item>
            <div class="button-group">
                <el-button @click="signIn" type="success" size="small">{{$t('admin.signIn.signIn')}}</el-button>
            </div>
        </el-form>
        <el-button id="switch-lang" @click="switchLang" type="success" size="mini"><i class="fa fa-language"/>&nbsp;{{$t('admin.signIn.signName')}}</el-button>
    </div>
</template>

<script>

import helper from '../../mixins/helper';

export default {

    name: 'signIn',

    mixins: [helper],

    data() {
        return {
            form: {},
        }
    },

    created() {

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

            this.poemRequest({
                type: 'post',
                url: '/admin?api=sign_in',
                data: {
                    username: this.form.name,
                    password: this.form.pwd,
                },
                success: (res) => {
                    if(res.data.code === 1) {
                        this.loadMenu();
                        this.$store.commit('changeSignInState', true);
                        this.$router.push(res.data.data.url);
                        sessionStorage.setItem('username', res.data.data.name);
                        this.$notify({ message:res.data.info, type:'success' });
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    }else {
                        this.$notify({ message:res.data.info, type:'warning'});
                    }
                },
            });
        },

        switchLang() {
            let lang = window.localStorage.getItem('sys-lang');
            switch (lang) {
                case 'en': {
                    window.localStorage.setItem('sys-lang', 'zh');
                    break;
                }
                case 'zh':
                default: {
                    window.localStorage.setItem('sys-lang', 'en');
                    break;
                }
            }
            window.location.reload();
        },
    }
}
</script>

<style lang="less">
    body {
        background: url('../../../static/imgs/signin-bg.png') 0 0 / cover no-repeat!important;
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
        position: relative;

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

        #switch-lang {
            position: absolute;
            bottom: 0;
            right: 0;
            display: inline-block;
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
