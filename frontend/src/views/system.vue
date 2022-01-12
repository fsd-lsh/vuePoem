<!--系统管理 - 系统监控-->

<include "../public/vue"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.11.1/less.min.js" ></script>

<style>
    #app .sta > .sta-item { margin:20px 0 0 0; }
    #app .sta > .sta-item .box-card { height:280px; max-height:280px; overflow-y:scroll; }
    #app .sta > .sta-item .box-card p:nth-child(1) { font-size:1rem; font-weight:600; }
</style>

<div id="app">

    <el-row :gutter="20" class="sta">

        <el-col :span="12" class="sta-item">
            <el-card class="box-card">
                <p>系统信息</p>
                <ul>
                    <li v-for="(item, key) in systemInfo.sys">
                        {{key}}: {{item}}
                    </li>
                </ul>
            </el-card>
        </el-col>

        <el-col :span="12" class="sta-item">
            <el-card class="box-card">
                <p>CPU</p>
                <ul>
                    <li v-for="(item, key) in systemInfo.cpu">
                        {{item}}
                    </li>
                </ul>
            </el-card>
        </el-col>

        <el-col :span="12" class="sta-item">
            <el-card class="box-card">
                <p>内存</p>
                <ul>
                    <li v-for="(item, key) in systemInfo.ram">
                        {{item}}
                    </li>
                </ul>
            </el-card>
        </el-col>

        <el-col :span="12" class="sta-item">
            <el-card class="box-card">
                <p>磁盘</p>
                <ul>
                    <li v-for="(item, key) in systemInfo.hdd">
                        {{item}}
                    </li>
                </ul>
            </el-card>
        </el-col>
    </el-row>
</div>

<script>


    let app = new Vue({

        el: '#app',

        data() {

            return {

                //系统监控信息
                systemInfo: {
                    sys: {},
                },
            }
        },

        created() {

            //加载系统监控信息
            setInterval(() => {
                this.loadSystemInfo();
            }, 1500);
        },

        methods: {

            //加载系统监控信息
            loadSystemInfo() {

                this.$http.post(
                    '/admin/system?api=load',
                    {},
                    {emulateJSON:true}
                ).then(function(res){
                    if(res.body.code === 1) {
                        this.systemInfo = res.body.data;
                    }
                },function(){
                    this.$notify.error({message: '服务器发生异常'});
                });
            },
        }
    });
</script>


