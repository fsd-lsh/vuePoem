<template>

    <div id="table-search">

        <div class="btn-group">
            <div class="curd">
                <el-select
                    v-if="pageLimit"
                    id="page-size"
                    size="mini"
                    @change="pageSizeChange"
                    v-model="formData.pageSize"
                    :placeholder="$t('admin.public.selectMenu')">
                    <el-option
                        v-for="(item, key) in pageSizeRange"
                        :key="key"
                        :value="item.value"
                        :label="item.label"/>
                </el-select>
                <slot name="btn"/>
            </div>
            <div class="control">
                <el-tooltip class="item" effect="dark" content="search" placement="top-start">
                    <el-button @click="changeOpenFlag" icon="fa fa-search" circle size="mini"></el-button>
                </el-tooltip>
                <el-tooltip class="item" effect="dark" content="refresh" placement="top-start">
                    <el-button @click="reloadTable" icon="fa fa-refresh" circle size="mini"></el-button>
                </el-tooltip>
            </div>
        </div>

        <div v-if="searchOpenFlag" class="search-form">
            <el-form :inline="true" :model="formData" class="demo-form-inline" size="mini">
                <el-form-item
                    v-for="(item, key) in formFormat"
                    :key="key"
                    :label="item.name">
                    <el-input
                        v-if="item.component === 'input'"
                        v-model="formData[item.remoteName]"
                        :placeholder="item.name"
                        :type="(item.componentType ? item.componentType : 'text')"/>
                    <el-select
                        v-if="item.component === 'select'"
                        v-model="formData[item.remoteName]"
                        :multiple="item.componentMultiple"
                        :placeholder="item.name">
                        <el-option v-if="!item.componentMultiple" key="" :label="'-->'+$t('admin.public.selectMenu')+'<--'" value/>
                        <el-option
                            v-for="(op_item, op_key) in item.componentOptions"
                            :key="op_key"
                            :label="op_item.name"
                            :value="op_item.id"/>
                    </el-select>
                    <el-date-picker
                        v-if="item.component === 'date-picker'"
                        v-model="formData[item.remoteName]"
                        :type="item.componentType"
                        value-format="timestamp"
                        range-separator="~"
                        start-placeholder="start"
                        end-placeholder="end">
                    </el-date-picker>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submit">{{$t('admin.public.query')}}</el-button>
                    <el-button @click="reloadTable">{{$t('admin.public.reset')}}</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
import helper from "../mixins/helper";
import merge from "webpack-merge";
export default {

    name: "vpTableSearch",

    mixins: [helper],

    props: {
        value: false,
        formFormat: [],
        pageLimit: false,
    },

    data() {
        return {
            searchOpenFlag: false,
            formData: {
                pageSize: 15,
            },
            pageSizeRange: [
                { value:15, label:15 },
                { value:50, label:50 },
                { value:100, label:100 },
                { value:150, label:150 },
                { value:500, label:500 },
            ],
        }
    },

    created() {

        let searchOpenFlag = window.localStorage.getItem('searchOpenFlag');
        if(searchOpenFlag === '1') {
            this.searchOpenFlag = true;
        }else {
            window.localStorage.setItem('searchOpenFlag', '0');
        }
    },

    methods: {

        changeOpenFlag() {
            this.searchOpenFlag = !this.searchOpenFlag;
        },

        reloadTable() {
            this.formData = {
                pageSize: this.formData.pageSize
            };
            this.$emit('reload');
        },

        submit() {
            this.$emit('submit', this.formData);
        },

        pageSizeChange() {

            this.$router.push({
                query: merge(this.$route.query, {
                    p: 1,
                    pageSize: this.formData.pageSize
                })
            });
            this.submit();
        },
    },

    watch: {

        value(v) {
            this.searchOpenFlag = v;
        },

        searchOpenFlag(v) {
            window.localStorage.setItem('searchOpenFlag', (v === true) ?  '1' : '0')
        },

        'formData.pageSize'(v) {
            this.formData.pageSize = Number(v);
        }
    },
}
</script>

<style scoped lang="less">

    #table-search {

        background: #f8f8f8;
        margin: 2px 0;
        border-radius: 5px;
        overflow: hidden;
        padding: 2px 0;

        > .btn-group {

            width: 100%;
            display: flex;

            > .curd {
                flex: .7;
                text-align: left;
                line-height: 26px;

                /deep/#page-size {
                    width: 74px;
                    height: 24px;
                    line-height: 24px;
                }

                /deep/.el-input__suffix {
                    top: 2px;
                }
            }

            >.control {
                flex: .3;
                text-align: right;
            }
        }

        > .search-form {
            padding: 5px 2px;

            > .el-form {

                > .el-form-item {

                    margin: 4px;

                    /deep/.el-form-item__label {
                        font-size: 12px;
                    }

                    /deep/.el-form-item__content {

                    }
                }
            }
        }
    }
</style>
