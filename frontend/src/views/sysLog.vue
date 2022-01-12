<!--系统管理 - 权限设置-->

<include "../public/vue"/>

<style>
    .el-table__row .cell { color:unset; }
</style>

<div id="app">

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
        <div class="pagination">
            {$page_html}
        </div>
    </el-card>
</div>

<script>
    let app = new Vue({

        el: '#app',

        data() {

            return {
                tableData: {$lists},
            }
        },

        methods: {

            tableStyle({row, column, rowIndex, columnIndex}) {

                if(column.label === '等级') {

                    switch (row.level) {
                        case '0': { return 'background:#009688; color:#fff!important;'; }
                        case '1': { return 'background:#FFB800; color:#fff!important;'; }
                        case '2': { return 'background:#e4393c; color:#fff!important;'; }
                    }
                }
            },
        },
    });
</script>
