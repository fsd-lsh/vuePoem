<template>
    <el-drawer
        :visible.sync="drawer"
        :show-close="true"
        :before-close="closeHandle"
        :with-header="false">
        <el-row
            id="config-panel"
            :gutter="20">

            <!--Set theme-->
            <el-col :span="24">
                <el-divider>{{$t('admin.public.setTheme')}}</el-divider>
            </el-col>
            <el-col
                :span="8"
                :key="key"
                v-for="(theme, key) in themeList"
                @click.native="themeHandleChange(theme)"
                class="config-panel-item">
                <div
                    :class="[
                        'grid-content',
                        theme,
                    ]">
                    <div class="top"></div>
                    <div class="left"></div>
                    <div class="right"></div>
                    <b class="fa fa-check-circle" v-if="(nowTheme === theme)"/>
                </div>
            </el-col>

            <!--Custom theme-->
            <el-col :span="24">
                <el-divider>{{$t('admin.public.customTheme')}}</el-divider>
            </el-col>
            <el-col
                :span="24"
                class="custom-theme">
                <el-form :model="customForm" label-width="180px" size="mini">

                    <el-form-item :label="$t('admin.theme.titleColor')">
                        <div
                            class="color-show"
                            :style="{ background:customForm.titleColor }"
                            @click="colorPickerFlag.titleColor = true"/>
                        <el-input class="color-input" v-model="customForm.titleColor" disabled/>
                        <color-picker
                            type="linear"
                            :showClose="true"
                            @changeColor="ccTitleColor"
                            :titleConfig="{show:true,text:$t('admin.theme.selectColor')}"
                            v-model="colorPickerFlag.titleColor"/>
                    </el-form-item>

                    <el-form-item :label="$t('admin.theme.mainColor')">
                        <div
                            class="color-show"
                            :style="{ background:customForm.mainColor }"
                            @click="colorPickerFlag.mainColor = true"/>
                        <el-input class="color-input" v-model="customForm.mainColor" disabled/>
                        <color-picker
                            type="linear"
                            :showClose="true"
                            @changeColor="ccMainColor"
                            :titleConfig="{show:true,text:$t('admin.theme.selectColor')}"
                            v-model="colorPickerFlag.mainColor"/>
                    </el-form-item>

                    <el-form-item :label="$t('admin.theme.logoBgColor')">
                        <div
                            class="color-show"
                            :style="{ background:customForm.logoBgColor }"
                            @click="colorPickerFlag.logoBgColor = true"/>
                        <el-input class="color-input" v-model="customForm.logoBgColor" disabled/>
                        <color-picker
                            type="linear"
                            :showClose="true"
                            @changeColor="ccLogoBgColor"
                            :titleConfig="{show:true,text:$t('admin.theme.selectColor')}"
                            v-model="colorPickerFlag.logoBgColor"/>
                    </el-form-item>

                    <el-form-item :label="$t('admin.theme.menuBgColor')">
                        <div
                            class="color-show"
                            :style="{ background:customForm.menuBgColor }"
                            @click="colorPickerFlag.menuBgColor = true"/>
                        <el-input class="color-input" v-model="customForm.menuBgColor" disabled/>
                        <color-picker
                            type="linear"
                            :showClose="true"
                            @changeColor="ccMenuBgColor"
                            :titleConfig="{show:true,text:$t('admin.theme.selectColor')}"
                            v-model="colorPickerFlag.menuBgColor"/>
                    </el-form-item>

                    <el-form-item :label="$t('admin.theme.menuFontNormalColor')">
                        <div
                            class="color-show"
                            :style="{ background:customForm.menuFontNormalColor }"
                            @click="colorPickerFlag.menuFontNormalColor = true"/>
                        <el-input class="color-input" v-model="customForm.menuFontNormalColor" disabled/>
                        <color-picker
                            type="linear"
                            :showClose="true"
                            @changeColor="ccMenuFontNormalColor"
                            :titleConfig="{show:true,text:$t('admin.theme.selectColor')}"
                            v-model="colorPickerFlag.menuFontNormalColor"/>
                    </el-form-item>

                    <el-form-item :label="$t('admin.theme.menuFontFocusColor')">
                        <div
                            class="color-show"
                            :style="{ background:customForm.menuFontFocusColor }"
                            @click="colorPickerFlag.menuFontFocusColor = true"/>
                        <el-input class="color-input" v-model="customForm.menuFontFocusColor" disabled/>
                        <color-picker
                            type="linear"
                            :showClose="true"
                            @changeColor="ccMenuFontFocusColor"
                            :titleConfig="{show:true,text:$t('admin.theme.selectColor')}"
                            v-model="colorPickerFlag.menuFontFocusColor"/>
                    </el-form-item>

                    <el-form-item :label="$t('admin.theme.menuFontActiveColor')">
                        <div
                            class="color-show"
                            :style="{ background:customForm.menuFontActiveColor }"
                            @click="colorPickerFlag.menuFontActiveColor = true"/>
                        <el-input class="color-input" v-model="customForm.menuFontActiveColor" disabled/>
                        <color-picker
                            type="linear"
                            :showClose="true"
                            @changeColor="ccMenuFontActiveColor"
                            :titleConfig="{show:true,text:$t('admin.theme.selectColor')}"
                            v-model="colorPickerFlag.menuFontActiveColor"/>
                    </el-form-item>

                    <el-form-item>
                        <el-button @click="addCustomTheme" type="primary" size="mini">{{$t('admin.theme.addCustomTheme')}}</el-button>
                        <el-button @click="resetCustomPicker" plain size="mini">{{$t('admin.public.reset')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </el-drawer>
</template>

<script>
import colorPicker from 'vue2-color-picker-gradient';
import helper from "../mixins/helper";
import theme from "../mixins/theme";

export default {

    name: "vpConfig",

    mixins: [helper, theme],

    props: {
        drawer: false,
        closeHandle: {
            type: Function,
            default: () => {},
        },
    },

    components: {colorPicker},

    data() {
        return {
            customForm: {},
            colorPickerFlag: {
                titleColor: false,
                mainColor: false,
                logoBgColor: false,
                menuBgColor: false,
                menuFontNormalColor: false,
                menuFontFocusColor: false,
                menuFontActiveColor: false,
            },
        }
    },

    created() {
        this.$nextTick(() => {
            this.customForm = {
                titleColor: '#fff',
                mainColor: '#fff',
                logoBgColor: '#fff',
                menuBgColor: '#fff',
                menuFontNormalColor: '#fff',
                menuFontFocusColor: '#fff',
                menuFontActiveColor: '#fff',
            };
        });
    },

    methods: {
        ccTitleColor({style, colors, deg, color}) {
            this.customForm.titleColor = color.hex;
        },
        ccMainColor({style, colors, deg, color}) {
            this.customForm.mainColor = color.hex;
        },
        ccLogoBgColor({style, colors, deg, color}) {
            this.customForm.logoBgColor = color.hex;
        },
        ccMenuBgColor({style, colors, deg, color}) {
            this.customForm.menuBgColor = color.hex;
        },
        ccMenuFontNormalColor({style, colors, deg, color}) {
            this.customForm.menuFontNormalColor = color.hex;
        },
        ccMenuFontFocusColor({style, colors, deg, color}) {
            this.customForm.menuFontFocusColor = color.hex;
        },
        ccMenuFontActiveColor({style, colors, deg, color}) {
            this.customForm.menuFontActiveColor = color.hex;
        },
        addCustomTheme() {
            let timestamp = (new Date()).getTime();
            let custom = JSON.parse(window.localStorage.getItem('sys-theme-custom'));
            if(!custom) {
                custom = {};
            }
            custom['custom-' + timestamp] = `
                .custom-${timestamp} {
                    --title-color: ${this.customForm.titleColor};
                    --main-color: ${this.customForm.mainColor};
                    --logo-bg-color: ${this.customForm.logoBgColor};
                    --menu-background-color: ${this.customForm.menuBgColor};
                    --menu-font-normal-color: ${this.customForm.menuFontNormalColor};
                    --menu-font-hover-color: ${this.customForm.menuFontFocusColor};
                    --menu-font-active-color: ${this.customForm.menuFontActiveColor};
                }
            `;
            this.resetCustomPicker();
            window.localStorage.setItem('sys-theme-custom', JSON.stringify(custom));

            let sysThemeLists = JSON.parse(window.sessionStorage.getItem('sys-theme-lists'));
            sysThemeLists.push(`custom-${timestamp}`)
            window.sessionStorage.setItem('sys-theme-lists', JSON.stringify(sysThemeLists));

            this.loadTheme(true);

            this.$notify({
                message: this.$t('admin.theme.addOk'),
                type: 'success'
            });
        },
        resetCustomPicker() {
            this.customForm = {
                titleColor: '#fff',
                mainColor: '#fff',
                logoBgColor: '#fff',
                menuBgColor: '#fff',
                menuFontNormalColor: '#fff',
                menuFontFocusColor: '#fff',
                menuFontActiveColor: '#fff',
            };
        },
    },

    watch: {
        drawer(v) {
            this.drawer = v;
            if(v) {
                this.resetCustomPicker();
            }
        },
    },
}
</script>

<style scoped lang="less">

#config-panel {

    padding: 10px;

    .config-panel-item {

        padding: 1.2rem 1.2rem 0 1.2rem;
        height: 86px;
        vertical-align:middle;
        text-align: center;
        transition: all linear .3s;
        cursor: pointer;

        .grid-content {

            display: block;
            height: 100%;
            line-height: 100%;
            width: 100%;
            border: 2px solid var(--main-color);
            border-radius: 5px;
            font-weight: 600;
            font-size: .9rem;
            overflow: hidden;
            position: relative;

            div.text {

                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                padding: .8rem;
            }

            > .top {
                height: 12px;
                width: 100%;
                background: var(--title-color);
            }

            > .left {
                height: 64px;
                width: 22.0%;
                display: inline-block;
                background: var(--menu-background-color);
            }

            > .right {
                height: 64px;
                width: 74.8%;
                display: inline-block;
                background: #fff;
            }

            > .fa-check-circle {
                position: absolute;
                font-weight: 700;
                bottom: 2px;
                right: 2px;
                font-size: 20px;
                border-radius: 100%;
                display: block;
            }
        }

        > .active {
            transition: all linear .3s;
            color: #fff;
            background: var(--main-color);
        }
    }

    .custom-theme {

        /deep/.el-form-item__content {

            display: flex;

            .color-show {
                display: inline-block;
                width: 24px;
                height: 24px;
                border: 1px solid #e5e5e5;
                border-radius: 4px;
                align-self: center;
                margin: 0 12px 0 0;
                &:hover {
                    cursor: pointer;
                }
            }

            .color-input {
                display: inline-block;
                align-self: center;
                width: 88%;
            }

            .color_picker_wrapper {
                z-index: 99999;
                position: absolute;
            }
        }
    }
}
</style>
