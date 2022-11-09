<template>
    <div>
        <el-drawer
            v-model="drawer"
            :show-close="true"
            :before-close="closeHandle"
            :modal="true"
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
                    @click="themeHandleChange(theme)"
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
                        <b class="fa fa-trash" @click="delCustomTheme(theme)" v-if="(nowTheme !== theme) && theme.slice(0, 6) === 'custom'"/>
                    </div>
                </el-col>

                <!--Custom theme-->
                <el-col :span="24">
                    <el-divider>{{$t('admin.public.customTheme')}}</el-divider>
                </el-col>
                <el-col
                    :span="24"
                    class="custom-theme">
                    <el-form :model="customForm" label-width="120px" size="small">

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
                                :pColor="{
                                    hex: customForm.titleColor,
                                    hex8: customForm.titleColor,
                                    color: hexToRgba(customForm.titleColor).rgba,
                                    rgba: {
                                        r: hexToRgba(customForm.titleColor).r,
                                        g: hexToRgba(customForm.titleColor).g,
                                        b: hexToRgba(customForm.titleColor).b,
                                        a: hexToRgba(customForm.titleColor).a,
                                    },
                                }"
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
                                :pColor="{
                                hex: customForm.mainColor,
                                hex8: customForm.mainColor,
                                color: hexToRgba(customForm.mainColor).rgba,
                                rgba: {
                                    r: hexToRgba(customForm.mainColor).r,
                                    g: hexToRgba(customForm.mainColor).g,
                                    b: hexToRgba(customForm.mainColor).b,
                                    a: hexToRgba(customForm.mainColor).a,
                                },
                            }"
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
                                :pColor="{
                                hex: customForm.logoBgColor,
                                hex8: customForm.logoBgColor,
                                color: hexToRgba(customForm.logoBgColor).rgba,
                                rgba: {
                                    r: hexToRgba(customForm.logoBgColor).r,
                                    g: hexToRgba(customForm.logoBgColor).g,
                                    b: hexToRgba(customForm.logoBgColor).b,
                                    a: hexToRgba(customForm.logoBgColor).a,
                                },
                            }"
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
                                :pColor="{
                                hex: customForm.menuBgColor,
                                hex8: customForm.menuBgColor,
                                color: hexToRgba(customForm.menuBgColor).rgba,
                                rgba: {
                                    r: hexToRgba(customForm.menuBgColor).r,
                                    g: hexToRgba(customForm.menuBgColor).g,
                                    b: hexToRgba(customForm.menuBgColor).b,
                                    a: hexToRgba(customForm.menuBgColor).a,
                                },
                            }"
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
                                :pColor="{
                                hex: customForm.menuFontNormalColor,
                                hex8: customForm.menuFontNormalColor,
                                color: hexToRgba(customForm.menuFontNormalColor).rgba,
                                rgba: {
                                    r: hexToRgba(customForm.menuFontNormalColor).r,
                                    g: hexToRgba(customForm.menuFontNormalColor).g,
                                    b: hexToRgba(customForm.menuFontNormalColor).b,
                                    a: hexToRgba(customForm.menuFontNormalColor).a,
                                },
                            }"
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
                                :pColor="{
                                hex: customForm.menuFontFocusColor,
                                hex8: customForm.menuFontFocusColor,
                                color: hexToRgba(customForm.menuFontFocusColor).rgba,
                                rgba: {
                                    r: hexToRgba(customForm.menuFontFocusColor).r,
                                    g: hexToRgba(customForm.menuFontFocusColor).g,
                                    b: hexToRgba(customForm.menuFontFocusColor).b,
                                    a: hexToRgba(customForm.menuFontFocusColor).a,
                                },
                            }"
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
                                :pColor="{
                                hex: customForm.menuFontActiveColor,
                                hex8: customForm.menuFontActiveColor,
                                color: hexToRgba(customForm.menuFontActiveColor).rgba,
                                rgba: {
                                    r: hexToRgba(customForm.menuFontActiveColor).r,
                                    g: hexToRgba(customForm.menuFontActiveColor).g,
                                    b: hexToRgba(customForm.menuFontActiveColor).b,
                                    a: hexToRgba(customForm.menuFontActiveColor).a,
                                },
                            }"
                            :titleConfig="{show:true,text:$t('admin.theme.selectColor')}"
                            v-model="colorPickerFlag.menuFontActiveColor"/>
                        </el-form-item>
                    </el-form>

                    <div class="btn-group">
                        <el-button @click="addCustomTheme" type="primary" size="small">{{$t('admin.theme.addCustomTheme')}}</el-button>
                        <el-button @click="exportCustomTheme" plain size="small">{{$t('admin.theme.exportCSS')}}</el-button>
                        <el-button @click="resetCustomTheme" plain size="small">{{$t('admin.public.reset')}}</el-button>
                    </div>
                </el-col>
            </el-row>

            <el-dialog
                :title="$t('admin.theme.exportCSS')"
                v-model="exportCSSFlag"
                :modal="false"
                width="30%">
                <span>{{$t('admin.theme.exportCSS')}}</span>
                <el-input
                    type="textarea"
                    :rows="11"
                    :placeholder="$t('admin.public.pleaseInput')"
                    v-model="exportCSSArticle">
                </el-input>
                <template #footer>
                    <span class="dialog-footer">
                        <el-button size="small" @click="exportCSSFlag = false">{{$t('admin.public.cancel')}}</el-button>
                    </span>
                </template>
            </el-dialog>
        </el-drawer>
    </div>
</template>

<script>
import colorPicker from 'vue3-color-picker-gradient';
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
            exportCSSFlag: false,
            exportCSSArticle: "",
        }
    },

    created() {
        this.$nextTick(() => {
            this.customForm = {
                titleColor: '#545C64',
                mainColor: '#FFD04B',
                logoBgColor: '#0c0c0c',
                menuBgColor: '#23262e',
                menuFontNormalColor: '#ffffff',
                menuFontFocusColor: '#ffffff',
                menuFontActiveColor: '#FFD04B',
            };
        });
    },

    methods: {
        ccTitleColor({style, colors, deg, color}) {
            this.customForm.titleColor = color.hex;
            this.refTemp();
        },
        ccMainColor({style, colors, deg, color}) {
            this.customForm.mainColor = color.hex;
            this.refTemp();
        },
        ccLogoBgColor({style, colors, deg, color}) {
            this.customForm.logoBgColor = color.hex;
            this.refTemp();
        },
        ccMenuBgColor({style, colors, deg, color}) {
            this.customForm.menuBgColor = color.hex;
            this.refTemp();
        },
        ccMenuFontNormalColor({style, colors, deg, color}) {
            this.customForm.menuFontNormalColor = color.hex;
            this.refTemp();
        },
        ccMenuFontFocusColor({style, colors, deg, color}) {
            this.customForm.menuFontFocusColor = color.hex;
            this.refTemp();
        },
        ccMenuFontActiveColor({style, colors, deg, color}) {
            this.customForm.menuFontActiveColor = color.hex;
            this.refTemp();
        },
        addCustomTheme() {

            this.$confirm('确认添加?', '', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {

                let timestamp = (new Date()).getTime();
                let custom = JSON.parse(window.localStorage.getItem('sys-theme-custom'));
                if(!custom) {
                    custom = {};
                }

                custom['custom-' + timestamp] =
                    '.custom-'+timestamp+' {' +
                    '--title-color:' + this.customForm.titleColor + ';' +
                    '--main-color:' + this.customForm.mainColor + ';' +
                    '--logo-bg-color:' + this.customForm.logoBgColor + ';' +
                    '--menu-background-color:' + this.customForm.menuBgColor + ';' +
                    '--menu-font-normal-color:' + this.customForm.menuFontNormalColor + ';' +
                    '--menu-font-hover-color:' + this.customForm.menuFontFocusColor + ';' +
                    '--menu-font-active-color:' + this.customForm.menuFontActiveColor + ';' +
                    '}';

                this.resetCustomTheme();
                window.localStorage.setItem('sys-theme-custom', JSON.stringify(custom));

                let sysThemeLists = JSON.parse(window.sessionStorage.getItem('sys-theme-lists'));
                sysThemeLists.push(`custom-${timestamp}`)
                sysThemeLists = sysThemeLists.filter((v, i, s) => {
                    return s.indexOf(v) === i;
                });
                window.sessionStorage.setItem('sys-theme-lists', JSON.stringify(sysThemeLists));

                this.loadTheme(true);

                this.$notify({
                    message: this.$t('admin.theme.addOk'),
                    type: 'success'
                });
            });
        },
        delCustomTheme(className) {
            window.localStorage.removeItem('sys-theme');
            let sysThemeCustom = JSON.parse(window.localStorage.getItem('sys-theme-custom'));
            delete sysThemeCustom[className];
            window.localStorage.setItem('sys-theme-custom', JSON.stringify(sysThemeCustom));

            this.loadTheme(true);

            this.$notify({
                message: this.$t('admin.theme.rmOk'),
                type: 'success'
            });
        },
        resetCustomTheme() {
            this.$nextTick(() => {
                this.customForm = {
                    titleColor: '#545C64',
                    mainColor: '#FFD04B',
                    logoBgColor: '#0c0c0c',
                    menuBgColor: '#23262e',
                    menuFontNormalColor: '#fff',
                    menuFontFocusColor: '#fff',
                    menuFontActiveColor: '#FFD04B',
                };
            });
            this.focusTheme();
        },
        exportCustomTheme() {
            this.exportCSSArticle = "";
            this.exportCSSFlag = true;
            let tempClassName = 'theme-temp-' + (new Date()).getTime();
            this.exportCSSArticle = `
.${tempClassName} {
    --title-color: ${this.customForm.titleColor};
    --main-color: ${this.customForm.mainColor};
    --logo-bg-color: ${this.customForm.logoBgColor};
    --menu-background-color: ${this.customForm.menuBgColor};
    --menu-font-normal-color: ${this.customForm.menuFontNormalColor};
    --menu-font-hover-color: ${this.customForm.menuFontFocusColor};
    --menu-font-active-color: ${this.customForm.menuFontActiveColor};
}
            `;
        },
        refTemp() {
            let head = document.getElementsByTagName('head');
            let styleLabel = document.createElement('style');
            let sysThemeTemp = document.getElementById('sys-theme-temp');
            if(sysThemeTemp) {
                head.item(0).removeChild(document.getElementById('sys-theme-temp'));
            }
            styleLabel.type = 'text/css';
            styleLabel.id = 'sys-theme-temp';
            styleLabel.innerHTML = `
                .theme-temp {
                    --title-color: ${this.customForm.titleColor};
                    --main-color: ${this.customForm.mainColor};
                    --logo-bg-color: ${this.customForm.logoBgColor};
                    --menu-background-color: ${this.customForm.menuBgColor};
                    --menu-font-normal-color: ${this.customForm.menuFontNormalColor};
                    --menu-font-hover-color: ${this.customForm.menuFontFocusColor};
                    --menu-font-active-color: ${this.customForm.menuFontActiveColor};
                }
            `;
            head.item(0).appendChild(styleLabel);
            document.querySelector('body').setAttribute('class', 'theme-temp');
        },
    },

    watch: {
        drawer(v) {
            if(v) {
                this.resetCustomTheme();
                this.refTemp();
            }else {
                this.focusTheme();
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
        height: 92px;
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
                width: 25.0%;
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

            >.fa-trash {
                font-weight: 700;
                position: absolute;
                bottom: 2px;
                right: 2px;
                font-size: 20px;
                display: block;
                border-radius: 100%;
                cursor: pointer;
            }
        }

        > .active {
            transition: all linear .3s;
            color: #fff;
            background: var(--main-color);
        }
    }

    .custom-theme {

        :deep(.el-form-item__content) {

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
                width: 84%!important;
            }

            .color_picker_wrapper {
                z-index: 99999;
                position: absolute;
            }
        }

        :deep(.el-input__wrapper) {
            width: 100%!important;
        }

        .btn-group {
            margin: 32px 0 0 0;
        }
    }
}
</style>
