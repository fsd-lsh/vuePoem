<template>
    <div id="sign-up" :class="nowTheme">
        <div class="head">{{$t('admin.signUp.signUp')}}</div>
        <el-form ref="form" :model="form" label-width="70px">
            <el-form-item :label="$t('admin.signIn.account')">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item :label="$t('admin.signIn.password')">
                <el-input v-model="form.pwd" show-password></el-input>
            </el-form-item>
            <el-form-item :label="$t('admin.signUp.rePassword')">
                <el-input v-model="form.rePwd" show-password></el-input>
            </el-form-item>
            <el-form-item :label="$t('admin.signIn.verifyCode')" style="position:relative;">
                <el-input v-model="form.vCode"></el-input>
                <img :src="vCodeImgSrc" @click="refVcode" class="verify-code" style="position:absolute; right:1px; top:1px; border-radius:4px;"/>
            </el-form-item>
            <div class="button-group">
                <el-button @click="signUp" type="success">{{$t('admin.signUp.signUp')}}</el-button>
            </div>
        </el-form>
        <el-link type="primary" @click="$router.push('/')"><i class="fa fa-arrow-left"/>&nbsp;{{$t('admin.signUp.hasAcc')}}</el-link>
    </div>
</template>

<script>
import helper from '../mixins/helper';
import theme from '../mixins/theme';
export default {
    name: "signUp",
    mixins:[helper, theme],
    data() {
        return {
            form: {
                name: '',
                pwd: '',
                rePwd: '',
                vCode: '',
            },
            dialogVisible: true,
            vCodeImgSrc: '',
        }
    },
    created() {
        this.refVcode();
    },
    methods: {
        handleClose() {
            this.form = {};
        },
        signUp() {
            if(!this.form.name) {
                this.liteNotice(0, this.$t('admin.user.enterAcc'));
                return false;
            }
            if(!this.form.pwd) {
                this.liteNotice(0, this.$t('admin.user.enterPwd'));
                return false;
            }
            if(this.form.pwd !== this.form.rePwd) {
                this.liteNotice(0, this.$t('admin.signUp.enterMatchPwd'));
                return false;
            }
            if(!this.form.vCode) {
                this.liteNotice(0, this.$t('admin.signIn.enterVCode'));
                return false;
            }
            this.poemRequest({
                type: 'post',
                url: '/admin?api=sign_up',
                data: {
                    username: window.btoa(this.form.name),
                    password: window.btoa(this.form.pwd),
                    rePassword: window.btoa(this.form.rePwd),
                    vCode: this.form.vCode,
                },
                success: (res) => {
                    if(res.data.code !== 1) {
                        this.liteNotice(0, res.data.info);
                    }
                    this.liteNotice(1, res.data.info);
                    setTimeout(() => {
                        this.$router.push('/');
                    }, 1500);
                }
            });
        },
        refVcode() {
            this.form.vCode = '';
            let rand = String(Math.random());
            rand = rand.slice(-8);
            this.vCodeImgSrc = '/admin?api=v_code&ver='+rand;
        },
    },
    watch: {

    },
}
</script>

<style lang="less">
    body {
        background: url('../../static/imgs/signin-bg.png') 0 0 / cover no-repeat!important;
        overflow: hidden;
    }
</style>

<style scoped lang="less">
    #sign-up {
        margin: 15% auto;
        background: #fff;
        width: 420px;
        height: 388px;
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
    }
</style>