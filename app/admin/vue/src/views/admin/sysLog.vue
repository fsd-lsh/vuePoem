<!--系统管理 - 系统日志-->

<template>

    <div id="sys-log">

        <poemAdmin>
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
                        :label="$t('admin.sysLog.user')">
                    </el-table-column>
                    <el-table-column
                        prop="path"
                        sortable
                        :label="$t('admin.sysLog.path')">
                    </el-table-column>
                    <el-table-column
                        prop="ctime"
                        sortable
                        :label="$t('admin.sysLog.ctime')">
                    </el-table-column>
                    <el-table-column
                        prop="level_mean"
                        sortable
                        width="80"
                        :label="$t('admin.sysLog.level')">
                    </el-table-column>
                </el-table>
                <div class="pagination" v-html="pageHtml"></div>
            </el-card>
        </poemAdmin>
    </div>
</template>

<script>

    import helper from "../../mixins/helper";

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

                //等级
                if(column.label === this.$t('admin.sysLog.level')) {

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
                    url: '/admin/log/sysLog?api=load&p=' + page,
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

<style lang="less" scoped>

</style>
