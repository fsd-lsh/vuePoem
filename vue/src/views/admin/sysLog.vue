<!--/sysLog-->
<template>
    <div id="sys-log">
        <vp-admin>
            <el-card class="box-card">
                <vp-table-search @reload="loadLists" @submit="search" :formFormat="formFormat" :pageLimit="true"/>
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
        </vp-admin>
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
                formFormat: [],
            }
        },

        created() {

            this.loadLists(
                this.parseGET()['p']
            );
        },

        watch: {

            $route: {
                handler() {
                    this.loadLists(
                        this.parseGET()['p']
                    );
                },
                deep: true,
            },

            tableData() {
                this.formFormat = [
                    { name:'ID', remoteName:'id', component:'input', componentType:'text' },
                    { name:this.$t('admin.sysLog.user'), remoteName:'admin_id', component:'input', componentType:'text' },
                    { name:this.$t('admin.sysLog.path'), remoteName:'path', component:'input', componentType:'text' },
                    { name:this.$t('admin.sysLog.ctime'), remoteName:'ctime', component:'date-picker', componentType:'daterange' },
                    { name:this.$t('admin.sysLog.level'), remoteName:'level', component:'select', componentOptions:[{id:1,name:this.$t('admin.sysLog.levelMean1')}, {id:2,name:this.$t('admin.sysLog.levelMean2')}, {id:0,name:this.$t('admin.sysLog.levelMean0')}], componentMultiple:false },
                ];
            },
        },

        methods: {

            tableStyle({row, column, rowIndex, columnIndex}) {
                if(column.label === this.$t('admin.sysLog.level')) {
                    switch (row.level) {
                        case '0': { return { 'background':'#009688', 'color':'#fff!important' }; }
                        case '1': { return { 'background':'#FFB800', 'color':'#fff!important' }; }
                        case '2': { return { 'background':'#e4393c', 'color':'#fff!important' }; }
                    }
                }
            },

            loadLists(page, query) {

                page = !page ? (page = 1) : page;
                query = !query ? (query = {}) : query;
                query = Object.assign(query, this.$route.query);

                this.poemRequest({
                    type: 'post',
                    url: '/admin/log/sysLog?api=load&p=' + page,
                    data: query,
                    success: (res) => {
                        if(res.data.code === 1) {
                            this.tableData = res.data.data.lists;
                            this.pageHtml = res.data.data.page_html;
                        }else {
                            this.$notify.error({message:res.data.info});
                        }
                    },
                });
            },

            search(query) {
                this.loadLists(this.parseGET()['p'], query);
            },
        },
    };
</script>

<style lang="less" scoped>

</style>
