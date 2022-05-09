# vuePoem

![](https://img.shields.io/badge/php-@phppoem-purple.svg?style=flat)
![](https://img.shields.io/badge/js-@vue-gree.svg?style=flat)
![](https://img.shields.io/badge/ui-@elementUI-blue.svg?style=flat)
![](https://img.shields.io/badge/font-@fontawesome-red.svg?style=flat)
![](https://img.shields.io/badge/License-@MIT-yellow.svg?style=flat)

> 一款轻量级，开源后台快速开发脚手架

## 项目计划（开发阶段）
|  任务/季度  | 2022 Q1 | 2022 Q2 | 2022 Q3 | 2022 Q4 |
|    ----    |   ----  |   ----  |   ----  |   ----  |
| 1 | 前后分离 | 国际化支持 | x | x |
| 2 | 基础功能重构 | 基础版本发布 | x | x |

## 项目简介
- vuePoem是一款轻量级，开源后台快速开发脚手架
- 基于vue + phppoem框架组合开发
- 如果您同时熟悉vue和php框架，可以以最短时间利用本脚手架开发项目
![show-0](app/admin/vue/static/imgs/show-0.png)
![show-1](app/admin/vue/static/imgs/show-1.png)
![show-2](app/admin/vue/static/imgs/show-2.png)
![show-3](app/admin/vue/static/imgs/show-3.png)

## 项目特性
- vue + php前后端分离
- 命令行工具（一键启动开发模式、一键打包部署模式）
- 开发即上手，接近0学习成本
- 系统拥有
  - 角色、菜单、用户基础权限模型，
  - 日志、系统监控
  - 自定义主题
  - 多国语支持

## 项目环境（推荐该版本及以上）
- PHP 7.4+
- Node v17.7.2 (npm v8.5.2) +
- MariaDB / Mysql

## 开发运行（以vuePoem项目目录为基准）

自动模式
```shell
# 1. 填写配置 `global.cnf`（根据你的实际情况修改vue、php、mysql配置）
vim global.cnf
# 2. 查看vuePoem命令行工具帮助，以 `vuePoem [option]` 格式执行选项功能
./vuePoem
# 3. 检查当前环境版本，推荐Php7.4+、Node17+、Mysql/MariaDB新版即可
./vuePoem check
# 4. 自动部署数据库，请确保您 `global.cnf` 文件mysql已配置，程序会自动创建所需数据库、数据表
./vuePoem mysql
# 5. 开发模式，程序自动帮您安装vue依赖、运行vue runtime server与php内建server联通
./vuePoem dev
# 6. 打包部署所需资源，vuePoem/public下会有打包后的静态资源
./vuePoem build
# 7. 清理打包资源
./vuePoem clean
```

手动模式
```shell
# 1. 填写配置 `global.cnf`（根据你的实际情况修改vue、php、mysql配置）
vim global.cnf
# 2. 创建你在`global.cnf`填写的`MYSQL_DBNM`对应数据库，将vuePoem.sql导入你的mysql，然后看一下有没有导入成功
mysql -uroot -pxxxxxx
show databases;
use {你的MYSQL_DBNM};
desc tables;
# 3. 安装vue 所需要的依赖
cd app/admin/vue && npm i
# 4. 启动PHP内建server，将下方命令`PHP_HOST`、`PHP_PORT`替换成`global.cnf`对应值
cd public && php -R {PHP_HOST}:{PHP_PORT} index.php
# 5. 启动vue开发server
cd app/admin/vue/ && npm run dev
```

## 部署运行(Nginx示范)
```shell
# 1.将vuePoem文件上传（部署工具、版本控制工具等）至服务器
# 2.配置web服务器（例如Nginx）根目录为vuePoem/public，以下是配置参考
server {
    listen       80;
    server_name  dev.vuePoem.force;
    root   /vuePoem/public/;
    index  index.html index.php;

    location /{
        if (!-e $request_filename) {
            rewrite  ^/(.*)$  /index.php/$1  last;
            break;
        }   
    } 

    location ~ \.php($|/) {
        fastcgi_pass   php:9000;
        fastcgi_index index.php;
        fastcgi_split_path_info  ^(.+\.php)(/.*)$;
        fastcgi_param  PATH_INFO $fastcgi_path_info;
        fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}
```

## 开发文档
编写中...

## 开源许可
[MIT](LICENSE)

## 作者
[Force](https://www.easybhu.cn)
