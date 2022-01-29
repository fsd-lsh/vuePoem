<!--系统管理 - 权限设置-->

<template>

    <div id="sys-log">

        <el-card class="box-card">
            <el-table
                :data="tableData"
                stripe
                size="mini"
                fit
                :highlight-current-row="true"
                :cell-style="tableStyle"
                style="width:100%">
                <el-table-column
                    prop="id"
                    width="100"
                    sortable
                    label="LOG ID">
                </el-table-column>
                <el-table-column
                    prop="admin"
                    sortable
                    label="记录用户">
                </el-table-column>
                <el-table-column
                    prop="path"
                    sortable
                    label="路径">
                </el-table-column>
                <el-table-column
                    prop="ctime"
                    sortable
                    label="创建时间">
                </el-table-column>
                <el-table-column
                    prop="level_mean"
                    sortable
                    width="80"
                    label="等级">
                </el-table-column>
            </el-table>
            <div class="pagination" v-html="pageHtml"></div>
        </el-card>
    </div>
</template>

<script>

    import helper from "../mixins/helper";

    export default {

        name: 'sysLog',

        mixins: [helper],

        data() {

            return {
                tableData: [],
                pageHtml: '',
            }
        },

        created() {

            //加载列表
            this.loadList(
                this.parseGET()['p']
            );
        },

        watch: {
            $route: {
                handler() {

                    //加载列表
                    this.loadList(
                        this.parseGET()['p']
                    );
                },
                deep: true,
            }
        },

        methods: {

            //列表行颜色切换
            tableStyle({row, column, rowIndex, columnIndex}) {

                if(column.label === '等级') {

                    switch (row.level) {
                        case '0': { return 'background:#009688; color:#fff!important;'; }
                        case '1': { return 'background:#FFB800; color:#fff!important;'; }
                        case '2': { return 'background:#e4393c; color:#fff!important;'; }
                    }
                }
            },

            //加载列表
            loadList(page) {

                page = (!page) ? (page = 1) : (page);

                this.poemRequest({
                    type: 'post',
                    url: '/admin/log/sys_log?api=load&p=' + page,
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.tableData = res.data.data.lists;
                            this.pageHtml = res.data.data.page_html;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            }
        },
    };
</script>

<style lang="less">

    @import "../../static/css/public";
    .el-table__row .cell { color:unset; }
</style>
