---
sidebarDepth: 3
---
# Development of the wizard

This chapter will introduce you to:
- Back-end part: API development, encapsulation function, configuration, routing
- Front-end: configuration, components, status, menu rendering
- Concept: permission model (user, role, permission), middleware (login, list component, restful API), I18N multi-language

## Back-end part (phpPoem)
This section describes the use of the back-end part in this project and notes. For more details, please visit the framework document [phpPoem](http://phppoem.com).

### API development
VuePoem mainly uses front and back separation, while phpPoem framework only uses MC (model & controller API) in this project although it supports MVC architecture.  
#### rpcAPI
In the vuePoem project, the encapsulating function 'SYS_API ([])' is mainly used to inject the array to disperse the API types, as follows:  
```php
namespace admin\controller;
class demo {
    public function user() {
        sys_api([
            'create_user' => function() {
                //API logic and Ajax responses
                $request = gp();
                ajax(1, 'ok', [1, 2, 3])
            },
            'delete_user' => function() {
                //API logic and Ajax responses
            }
        ]);
    }
}
```
Assuming the demo function for ` app/admin/controller/demo.php `, including namespace for `namespace admin\controller;` Expect access the create_user, by way of web requests to: `http://localhost:port/admin/demo/user?api=create_user`to access the interface  
#### restfulAPI
```php
namespace admin\controller;
class demo {
    public function user() {
        restful([
            'get' => function($restful) {
                $request = $restful->request();
                $restful->response(1, 'succ', ['arr' => [1,2,3]]); //1｜0 == 200｜500
                //$restful->response(0, 'err');
            },
            'post' => function($restful) {/*API logic and Ajax responses*/},
            'put' => function($restful) {/*API logic and Ajax responses*/},
            'delete' => function($restful) {/*API logic and Ajax responses*/},
        ]);
    }
}
```
Use the `restful([])` function to inject an array. Array keys must be supported in `app/config.php` restful_method.
Restful callbacks are passed through anonymous function parameters. You can directly use the wrapped request function `$restful->request()` and response function `$restful->response(1, 'succ', ['arr' => [1,2,3]])`.  
Response function in the parameters passed in order ` $restful - > response (response code/HTTP status code, information, data) `, specific you can directly see restful middleware source ` ./server/app/service/middleware/restful.php `

### function
PHP wrapper functions have two paths (both of which can be called globally within the controller). In principle, the 「phpPoem wrapper function」 should not be modified or added as much as possible.  
You need to extend the function yourself when writing the back-end interface. You need to add to the 「vuePoem encapsulation function」.  
- 「phpPoem wrapper function」 `./server/app/function.php`
- 「vuePoem encapsulation function」`./server/phppoem/function.php` 

### configuration
The configuration also has two paths, called in the `config('key')` mode within the controller  
「VuePoem Configuration」 stores the reserved configuration of the project and your custom configuration  
「PhpPoem configuration」 stores the database, session and other configurations. The database related call `.env` position should not be modified as far as possible  
- 「VuePoem Configuration」`./server/app/config.php`
- 「PhpPoem configuration」`./server/phppoem/config.php`

### Back-end routing
Here is not recommended to modify, if have special requirements please visit [phpPoem routing](https://phppoem.com/docs/17.html)

## Front End (vue)
This chapter will explain the front end of vuePoem for you

### configuration
Configuration is located in the`./vue/config/index.js`  
In development mode, vuePoem preproxies the back-end API interface for you and is based on the `.env` file. In the case of back-end service startup, you can access the back-end interface directly through the front-end proxy.  
In the construction mode, static resources will be uniformly output to the `./server/public` directory  
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

### component
VuePoem reserves two components for you, both imported globally without having to manually introduce them again
- Structural components of the background enclosure
This component is used for background common interface development. The component contains navigation, side menu, header, and tail common parts. You only need to call it as follows  
```vue
<template>
     <div id="demo">
        <vp-admin>
            ...page content
        </vp-admin>
    </div>
</template>
```
- List paging component
This component works with back-end middleware  
```php
//For example, this controller file is placed in vuewPoem/app/admin/controller/demo.php
namespace admin\controller;
use \service\middleware;
class demo
{
    public function user() {
        //调The table list middleware is called, and the constructor passes in the table name
        $table = new middleware\table('sys_admin'); //Example Querying a table
        sys_api([
            //Load the list, and optionally the third callback handles the data
            'lists' => $table->lists('/#/demo', 15, function($data) {
                if(is_array($data) && !empty($data)) {
                    foreach ($data as $key => $item) {
                        //...
                    }
                    return $data;
                }
            }),

            //If the status of a single row of data is changed, the parameter is passed according to the default rule
            'status' => $table->status(),

            //To add or update data, pass parameters according to the default rules
            'save' => $table->save(),
        ]);
    }
}
```

```vue
//For example, this template file is placed in vuewPoem/app/admin/vue/src/views/admin/demo.vue
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
            url: '/admin/demo/user',    //Location of the back-end interface
            dialog: [],                 //For details about how to create and edit components, see Components
            rowView: {                  
                viewEnable: true,
                viewCustom: false,
            },
        }
    },
}
</script>
```
More features need to read two parts of the source code to understand the principle  
Backend: `./server/app/service/middleware/table.php`  //Requires you to use `sys_api()` in the controller instantiation
Frontend: ` ./server/app/admin/vue/SRC/components/table.vue ` //You need to call `<poemTable>` on the new page

### Status management
VuePoem projects using vuex as state management, path in `./server/app/admin/vue/store/index.js`  
```js
import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);
const store = new Vuex.Store({
    state: {},
    mutations: {
        changeSignInState(state, bool) {
            state.isSignIn = bool;
        },
        setMenuTree(state, bool) {
            state.menuTree = bool;
        },
    },
    getters:{
        getMenuTree(state) {
            return state.menuTree;
        },
    },
});
export default store;
```

### Menu rendering (front-end routing)
VuePoem adds a menu binding dynamic route to the route location. After adding the front-end page, you need to enter the system to add a menu and configure role rights before you can access it normally
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

## Concept Part (vuePoem)

### Permission model
- The admin user has a role
- Rols character has a menu
- menu Indicates that the menu restricts front-end routes

### middleware
Path ` ./server/app/service/middleware/ `, please read the source code

### i18N Many languages
VuePoem using common language pack before and after the end, path `./common/lang`  
Vue uses the `this.$t('admin.xxx.xxx')` access language and PHP uses the `tran('admin.xxx.xxx')` access language
