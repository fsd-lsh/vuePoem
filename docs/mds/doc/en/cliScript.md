# CLI auxiliary scripts

VuePoem provides an auxiliary script to reduce the complexity of getting started. It can help you create databases, start front-end and back-end services, build static resources in the production environment, install dependencies, check version, clean up content after construction, etc.The script location is: `vuepoem/`, make sure you run it in this directory.
The script is written in PHP and can be extended as needed.

## Enable PHP functions
The `exec,system,` function are required in the script. Check the 'php.ini' file to ensure that the `exec` and `system` functions are not disabled in the `disable_functions` entry  
```shell
# Check the php.ini path
php -i|grep ini

# Edit and save
vim /xxx/xxx/xxx/php.ini
```

## help
```
./vuePoem help
```
Example of prompt after run
```text
vuePoem cli help (vuePoem [option])
--------------------------------------
check  :Checking the Operating Environment
dev    :Automatic loading of Vue and PHP development servers
build  :Vue projects are packaged as static resources in public/
clean  :Clean up build front-end resources
mysql  :Automatic Database deployment
```

## Check the version and required directory
Check the critical path and environment of the project before the first startup
```shell
./vuePoem check
```
Example of prompt after run
```text
path  : [ok:.env] [ok:./server/public/index.php] [ok:./server/phppoem/core] [ok:./server/app/admin/controller] [ok:./server/app/service/middleware] [ok:/Users/force/Desktop/Project/mine/vuePoem/vue] 
php   : PHP 7.4.30 (cli) (built: Jun  9 2022 09:30:03) ( NTS ) - Copyright (c) The PHP Group - Zend Engine v3.4.0, Copyright (c) Zend Technologies -     with Zend OPcache v7.4.30, Copyright (c), by Zend Technologies
node  : v8.10.0
npm   : 5.6.0
mysql : mysql  Ver 14.14 Distrib 5.7.39, for osx10.17 (x86_64) using  EditLine wrapper
```

## Create a database
Ensure that Mysql is correctly configured in the `vuePoem/.env` file before using automatic database creation
```shell
./vuePoem mysql
```
Example of prompt after run
```text
vuePoem: 2022-xx-xx xx:xx:xx - mysql db & tables init success 
vuePoem: 2022-xx-xx xx:xx:xx - bye! 
```

## Start Development mode
```shell
./vuePoem dev
```
Example of prompt after run
```text
vuePoem: xxxx-xx-xx xx:xx:xx - dev running... 
vuePoem: xxxx-xx-xx xx:xx:xx - press ctrl+c to quit 
vuePoem: xxxx-xx-xx xx:xx:xx - cd public && php -S 127.0.0.1:8899 index.php & cd app/admin/vue && npm run dev 
[Thu Aug 25 11:29:35 2022] PHP 7.4.30 Development Server (http://127.0.0.1:8899) started

> vue_part@1.0.0 dev /Users/force/Desktop/Project/mine/vuePoem/app/admin/vue
> webpack-dev-server --open --inline --progress --config build/webpack.dev.conf.js

 16% building modules 51/103 modules 52 active ...dmin/vue/src/views/admin/userInfo.vue{ parser: "babylon" } is deprecated; we now treat it as { parser: "babel" }.
 95% emitting DONE  Compiled successfully in 6477ms11:29:44 AM                          

 I  View: http://127.0.0.1:7777
 I  API: http://127.0.0.1:8899
[Thu Aug xx xx:xx:xx xxxx] 127.0.0.1:58713 Accepted
[Thu Aug xx xx:xx:xx xxxx] 127.0.0.1:58713 Closing
```

## Build production static resources
To construct front-end resources for production environment, the script will construct resources under `vuepoem/public/`
```shell
./vuePoem build
```
Example of prompt after run
```text
vuePoem: xxxx-xx-xx xx:xx:xx - build running... 
vuePoem: xxxx-xx-xx xx:xx:xx - cd app/admin/vue && npm run build 

> vue_part@1.0.0 build /Users/force/Desktop/Project/mine/vuePoem/app/admin/vue
> node build/build.js
⠹ building for production...Browserslist: caniuse-lite is outdated. Please run:
  npx browserslist@latest --update-db
  Why you should do it regularly: https://github.com/browserslist/browserslist#browsers-data-updating
Browserslist: caniuse-lite is outdated. Please run:
  npx browserslist@latest --update-db
  Why you should do it regularly: https://github.com/browserslist/browserslist#browsers-data-updating
Hash: 4246a9c46f64f350682e
Version: webpack 3.12.0
Time: 18857ms

                                                  Asset     Size  Chunks                    Chunk Names
                       static/img/signin-bg.a9bc121.png  59.3 kB          [emitted]         
             static/img/fontawesome-webfont.912ec66.svg   444 kB          [emitted]  [big]  
           static/fonts/fontawesome-webfont.b06871f.ttf   166 kB          [emitted]         
           static/fonts/fontawesome-webfont.674f50d.eot   166 kB          [emitted]         
          static/fonts/fontawesome-webfont.fee66e7.woff    98 kB          [emitted]         
         static/fonts/fontawesome-webfont.af7ae50.woff2  77.2 kB          [emitted]         
                static/fonts/element-icons.535877f.woff  28.2 kB          [emitted]         
                 static/fonts/element-icons.732389d.ttf    56 kB          [emitted]         
                    static/js/0.5c8e831fdfc2600886bb.js  46.8 kB    0, 2  [emitted]         
                    static/js/1.d04a32c88c26cebea500.js  2.49 kB       1  [emitted]         
                    static/js/2.741fbc163f00c4a1c6b6.js  3.08 kB       2  [emitted]         
               static/js/vendor.eb654fd00e76d43607ee.js  1.03 MB       3  [emitted]  [big]  vendor
                  static/js/app.0cf580606e551eed7e49.js  31.5 kB       4  [emitted]         app
             static/js/manifest.099573f772f5524d3a4f.js  1.51 kB       5  [emitted]         manifest
    static/css/app.2011d6af09edcd4e52ae60cbf7e5c295.css   277 kB       4  [emitted]  [big]  app
static/css/app.2011d6af09edcd4e52ae60cbf7e5c295.css.map   396 kB          [emitted]         
                static/js/0.5c8e831fdfc2600886bb.js.map   188 kB    0, 2  [emitted]         
                static/js/1.d04a32c88c26cebea500.js.map  11.7 kB       1  [emitted]         
                static/js/2.741fbc163f00c4a1c6b6.js.map  12.5 kB       2  [emitted]         
           static/js/vendor.eb654fd00e76d43607ee.js.map  4.19 MB       3  [emitted]         vendor
              static/js/app.0cf580606e551eed7e49.js.map   106 kB       4  [emitted]         app
         static/js/manifest.099573f772f5524d3a4f.js.map  7.84 kB       5  [emitted]         manifest
                                             index.html  2.22 kB          [emitted]         
                                 static/css/public.less  5.94 kB          [emitted]         
                                  static/css/theme.less  1.27 kB          [emitted]         
                              static/imgs/signin-bg.png  59.3 kB          [emitted]         
                                 static/imgs/show-2.png   343 kB          [emitted]  [big]  
                                 static/imgs/show-3.png   391 kB          [emitted]  [big]  
                                 static/imgs/show-1.png   469 kB          [emitted]  [big]  
                          static/other/fontawesome.json  35.5 kB          [emitted]         
                                 static/imgs/show-0.png  2.37 MB          [emitted]  [big]  

  Build complete.

  Tip: built files are meant to be served over an HTTP server.
  Opening index.html over file:// won't work.

vuePoem: xxxx-xx-xx xx:xx:xx - build finished 
vuePoem: xxxx-xx-xx xx:xx:xx - bye! 
```

## Clean up static resources after the build
Clean the static resource constructed by the `./vuePoem build` command in `vuePoem /public`
```shell
./vuePoem clean
```
Example of prompt after run
```text
vuePoem: xxxx-xx-xx xx:xx:xx - rm -rf public/index.html 
vuePoem: xxxx-xx-xx xx:xx:xx - rm -rf public/static/css 
vuePoem: xxxx-xx-xx xx:xx:xx - rm -rf public/static/fonts 
vuePoem: xxxx-xx-xx xx:xx:xx - rm -rf public/static/img 
vuePoem: xxxx-xx-xx xx:xx:xx - rm -rf public/static/imgs 
vuePoem: xxxx-xx-xx xx:xx:xx - rm -rf public/static/js 
vuePoem: xxxx-xx-xx xx:xx:xx - clean success 
vuePoem: xxxx-xx-xx xx:xx:xx - bye! 
```
