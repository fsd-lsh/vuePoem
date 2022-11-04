---
sidebarDepth: 3
---
# 开发向导

本章节将为您介绍：
- 后端部分：API开发、封装函数、配置、路由
- 前端部分：配置、组件、状态、菜单渲染
- 概念部分：权限模型（用户、角色、权限）、中间件（登录、列表组件、restful API）、i18n多国语

## 后端部分 (phpPoem)
此部分主要简述后端部分在本项目中使用说明及注意事项，更多详情请访问框架文档[phpPoem](http://phppoem.com)

### API开发
vuePoem主要采用前后分离，phpPoem框架虽支持mvc架构在本项目中只用到mc(既模型&控制器组成API)。  
#### rpcAPI方式
vuePoem项目中主要使用封装函数`sys_api([])`注入数组来分散API类型，如下：  
```php
namespace admin\controller;
class demo {
    public function user() {
        sys_api([
            'create_user' => function() {
                //API逻辑及ajax响应
                $request = gp();
                ajax(1, 'ok', [1, 2, 3])
            },
            'delete_user' => function() {
                //API逻辑及ajax响应
            }
        ]);
    }
}
```
假设demo函数为`app/admin/controller/demo.php`，其中命名空间为`namespace admin\controller;`情况下期望访问create_user通过web请求方式为：`http://localhost:port/admin/demo/user?api=create_user`即可访问接口  
#### restfulAPI方式
```php
namespace admin\controller;
class demo {
    public function user() {
        restful([
            'get' => function($restful) {
                //接收前端请求
                $request = $restful->request();

                //响应
                $restful->response(1, 'succ', ['arr' => [1,2,3]]); //1｜0 == 200｜500
                //$restful->response(0, 'err');
            },
            'post' => function($restful) {/*API逻辑及ajax响应*/},
            'put' => function($restful) {/*API逻辑及ajax响应*/},
            'delete' => function($restful) {/*API逻辑及ajax响应*/},
        ]);
    }
}
```
使用`restful([])`函数注入数组。数组键必须在`app/config.php`restful_method中被支持。  
restful回调通过匿名函数参数中传递，您可直接使用已经封装好的请求函数`$restful->request()`以及响应函数`$restful->response(1, 'succ', ['arr' => [1,2,3]]);`。  
响应函数中传递的参数依次为`$restful->response(响应代号/http状态码，信息，数据)`，具体您可直接查看restful中间件源代码`./server/app/service/middleware/restful.php`

### 封装函数
php封装函数有两个path(都可在控制器内全局调用)，原则上「phpPoem封装函数」尽量不要修改或添加。  
在写后端接口过程中需要自己扩展函数您需要向「vuePoem封装函数」添加。  
- 「vuePoem封装函数」 `./server/app/function.php`
- 「phpPoem封装函数」`./server/phppoem/function.php` 

### 配置
配置同样有两个path，在控制器内以`config('key')`方式调用  
「vuePoem配置」存放项目预留配置和您自定义配置  
「phpPoem配置」存放数据库、session等配置，数据库相关涉及调用`.env`位置尽量不要修改
- 「vuePoem配置」`./server/app/config.php`
- 「phpPoem配置」`./server/phppoem/config.php`

### 后端路由
此处不推荐修改，如有特殊需求请访问[phpPoem路由](https://phppoem.com/docs/17.html)

## 前端部分 (vue)
此章节将为您阐述vuePoem前端部分

### 配置
配置位于`./vue/config/index.js`  
开发模式下，vuePoem为您预先代理后端API接口并基于`.env`文件。在后端服务启动情况下，您可以直接通过前端代理访问后端接口。  
构建模式下，静态资源会统一向`./server/public`目录输出
```javascript
'use strict'
const path = require('path');
const ini = require('ini');
const fs = require('fs');
let str = fs.readFileSync(path.resolve(__dirname, '../../.env')).toString();
let env = ini.parse(str);
module.exports = {
    dev: {
        assetsSubDirectory: 'static',
        assetsPublicPath: '/',
        proxyTable: {
            '/admin': {
                target: 'http://' + env.PHP_HOST + ':' + env.PHP_PORT,
                changeOrigin: true,
                pathRewrite: {
                    '^/admin': '/admin'
                }
            }
        },
        host: env.VUE_HOST,
        port: env.VUE_PORT,
        autoOpenBrowser: false,
        errorOverlay: true,
        notifyOnErrors: true,
        poll: false,
        devtool: 'cheap-module-eval-source-map',
        cacheBusting: true,
        cssSourceMap: true
    },

    build: {
        index: path.resolve(__dirname, '../../server/public/index.html'),
        assetsRoot: path.resolve(__dirname, '../../server/public'),
        assetsSubDirectory: 'static',
        assetsPublicPath: '/',
        productionSourceMap: true,
        devtool: '#source-map',
        productionGzip: true,
        productionGzipExtensions: ['js', 'css'],
        bundleAnalyzerReport: process.env.npm_config_report
    }
}
```

### 组件
vuePoem为您预留两种组件，均以全局导入无需再次手动引入
- 后台框体结构组件  
此组件用于后台通用界面开发，组件包含导航、侧边菜单、头、尾通用部分您只需要像如下方式调用即可
```vue
<template>
     <div id="demo">
        <vp-admin>
            ...页面内容
        </vp-admin>
    </div>
</template>
```
- 列表分页组件  
此组件与后端中间件配合使用
```php
//例如此控制器文件放置在 vuewPoem/app/admin/controller/demo.php
namespace admin\controller;
use \service\middleware;
class demo
{
    public function user() {
        //调用表格列表中间件，构造函数传入表名
        $table = new middleware\table('sys_admin'); //举例查询某表
        sys_api([
            //加载列表, 第三位回调处理数据可选
            'lists' => $table->lists('/#/demo', 15, function($data) {
                if(is_array($data) && !empty($data)) {
                    foreach ($data as $key => $item) {
                        //...
                    }
                    return $data;
                }
            }),

            //单行数据状态修改，按照规则默认传参即可
            'status' => $table->status(),

            //添加或更新数据，按照规则默认传参即可
            'save' => $table->save(),
        ]);
    }
}
```

```vue
//例如此模版文件放置于 vuewPoem/app/admin/vue/src/views/admin/demo.vue
<template>
     <div id="demo">
        <poemTable
            :url="url"
            :dialog="dialog"
            :rowView="rowView"/>
    </div>
</template>
<script>
export default {
    name: 'demo',
    data() {
        return {
            url: '/admin/demo/user',    //后端接口位置
            dialog: [],                 //请参考组件，提供新建、编辑功能
            rowView: {                  
                viewEnable: true,
                viewCustom: false,
            },
        }
    },
}
</script>
```
更多功能需要阅读两部分源码了解原理  
后端：`./server/app/service/middleware/table.php` //需要您在控制器实例化配合`sys_api()`使用
前端：`./vue/src/components/table.vue` //需要您在新页面调用`<poemTable>`

### 状态管理
vuePoem项目使用vuex作为状态管理，路径位于`./vue/store/index.js`  
```js
import Vue from 'vue';
import Vuex from 'vuex';

//挂载Vuex
Vue.use(Vuex);

//创建VueX对象
const store = new Vuex.Store({

    state: {},

    mutations: {

        //修改登录状态
        changeSignInState(state, bool) {
            state.isSignIn = bool;
        },

        //设置菜单树
        setMenuTree(state, bool) {
            state.menuTree = bool;
        },
    },

    getters:{

        //获取菜单树
        getMenuTree(state) {
            return state.menuTree;
        },
    },
});
export default store;
```

### 菜单渲染(前端路由)
vuePoem在路由位置添加菜单绑定动态路由，您在添加完前端页面后需要进入系统添加菜单配置角色权限后才能正常访问
```js
import Vue from 'vue';
import Router from 'vue-router';
import axios from 'axios';
Vue.use(Router);
let router = new Router({
    mode:'hash',
    routes: [
        {
            path: '/',
            name: 'signIn',
            component: resolve => require(['@/views/signIn'], resolve),
            meta: {
                title: '',
            },
        },
    ],
});

const lang = localStorage.getItem('sys-lang');
axios.get('/admin/menu/load?lang='+lang).then(res => {

    if(res.data.code === 955) {
        sessionStorage.setItem('store', JSON.stringify({isSignIn:false,menuTree:{}}));
        if(window.location.hash !== '#/') {
            window.location.href = '/';
        }
        return false;
    }

    let menu = res.data.data.menuInfo;
    let logoInfo = res.data.data.logoInfo;
    let store = JSON.parse(window.sessionStorage.getItem('store'));
    store.menuTree.menuInfo = menu;
    window.sessionStorage.setItem('store', JSON.stringify(store));

    if(res.data.code === 1) {
        for (let index = 0; index < menu.length; index++) {
            for (let sub_index = 0; sub_index < menu[index].child.length; sub_index++) {
                if(menu[index].child[sub_index].child.length) {
                    for (let sub_index2 = 0; sub_index2 < menu[index].child[sub_index].child.length; sub_index2++) {
                        router.addRoute({
                            path: menu[index].child[sub_index].child[sub_index2].href,
                            name: menu[index].child[sub_index].child[sub_index2].name,
                            component: resolve => require(['@/views/admin/' + menu[index].child[sub_index].child[sub_index2].name], resolve),
                            meta: {
                                title: menu[index].child[sub_index].child[sub_index2].title + ' - ' + logoInfo.title,
                            },
                        });
                    }
                }else {
                    router.addRoute({
                        path: menu[index].child[sub_index].href,
                        name: menu[index].child[sub_index].name,
                        component: resolve => require(['@/views/admin/' + menu[index].child[sub_index].name], resolve),
                        meta: {
                            title: menu[index].child[sub_index].title + ' - ' + logoInfo.title,
                        },
                    });
                }
            }
        }
    }
});
export default router;
```

## 概念部分 (vuePoem)

### 权限模型
- admin 用户拥有角色
- rols  角色拥有菜单
- menu  菜单限制前端路由

### 中间件
路径`./server/app/service/middleware/`，请阅读源码

### i18n多国语
vuePoem采用前后端公用语言包，路径`./common/lang`  
vue中使用`this.$t('admin.xxx.xxx')`访问语言，php中使用`tran('admin.xxx.xxx')`访问语言
