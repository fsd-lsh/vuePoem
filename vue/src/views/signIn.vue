<!--系统管理 - 登录-->
<template>
    <div id="sign-in" :class="nowTheme">
        <div class="head">{{$t('admin.signIn.title')}}</div>
        <el-form ref="form" :model="form" label-width="65px">
            <el-form-item :label="$t('admin.signIn.account')">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item :label="$t('admin.signIn.password')">
                <el-input v-model="form.pwd" show-password></el-input>
            </el-form-item>
            <el-form-item :label="$t('admin.signIn.verifyCode')" style="position:relative;">
                <el-input v-model="form.vCode"></el-input>
                <img :src="vCodeImgSrc" @click="refVcode" class="verify-code" style="position:absolute; right:1px; top:1px; border-radius:4px;"/>
            </el-form-item>
            <div class="button-group">
                <el-button @click="signIn" type="success">{{$t('admin.signIn.signIn')}}</el-button>
            </div>
        </el-form>
        <el-link id="sign-up" type="primary" @click="this.$router.push('/signUp')"><i class="fa fa-sitemap"/>&nbsp;{{$t('admin.signUp.signUp')}}</el-link>
        <el-link id="switch-lang" type="primary" @click="switchLang"><i class="fa fa-language"/>&nbsp;{{$t('admin.signIn.signName')}}</el-link>
    </div>
</template>

<script>

import helper from '../mixins/helper';
import theme from '../mixins/theme';

export default {

    name: 'signIn',

    mixins: [helper, theme],

    data() {
        return {
            form: {
                name: '',
                pwd: '',
                vCode: '',
            },
            vCodeImgSrc: '',
        }
    },

    created() {
        this.refVcode();
    },

    methods: {

        signIn() {

            if(!this.form.name) {
                this.liteNotice(0, this.$t('admin.user.enterAcc'));
                return false;
            }
            if(!this.form.pwd) {
                this.liteNotice(0, this.$t('admin.user.enterPwd'));
                return false;
            }
            if(!this.form.vCode) {
                this.liteNotice(0, this.$t('admin.signIn.enterVCode'));
                return false;
            }

            this.poemRequest({
                type: 'post',
                url: '/admin?api=sign_in',
                data: {
                    username: window.btoa(this.form.name),
                    password: window.btoa(this.form.pwd),
                    vCode: this.form.vCode,
                },
                success: (res) => {

                    if(res.data.code === 1) {

                        this.loadMenu();

                        this.$store.commit('changeSignInState', true);
                        let store = JSON.parse(window.sessionStorage.getItem('store'));
                        store.isSignIn = true;

                        sessionStorage.setItem('store', JSON.stringify(store));
                        sessionStorage.setItem('username', res.data.data.name);

                        this.$notify({message:res.data.info, type:'success'});

                        this.$router.push(res.data.data.url);
                        this.$router.go(0);
                    }else {
                        this.refVcode();
                        this.$notify({ message:res.data.info, type:'warning'});
                    }
                },
            });
        },

        refVcode() {
            this.form.vCode = '';
            let rand = String(Math.random());
            rand = rand.slice(-8);
            this.vCodeImgSrc = '/admin?api=v_code&ver='+rand;
        },
    },
}
</script>

<style lang="less">
    body {
        background: url('../../static/imgs/signin-bg.png') 0 0 / cover no-repeat!important;
        overflow: hidden;
    }
</style>

<style lang="less" scoped>

    #sign-in {

        margin: 15% auto;
        background: #fff;
        width: 420px;
        height: 308px;
        border-radius: 12px;
        position: relative;

        .head {
            background-color: var(--title-color);
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

        #sign-up {
            position: absolute;
            bottom: 5px;
            right: 5px;
            display: inline-block;
        }

        #switch-lang {
            position: absolute;
            bottom: 5px;
            right: 90px;
            display: inline-block;
        }
    }

    .el-form {

        padding: 25px;

        .el-form-item {
            margin: 0 0 16px 0;
        }

        .button-group {

            text-align: center;
            margin: 28px 0 0 0;

            > button {
                width: 100%;
                background: var(--main-color);
                border-color: var(--main-color);
            }
        }
    }
</style>
