# vuePoem

![](https://img.shields.io/badge/php-@phppoem-purple.svg?style=flat)
![](https://img.shields.io/badge/js-@vue-gree.svg?style=flat)
![](https://img.shields.io/badge/ui-@elementUI-blue.svg?style=flat)
![](https://img.shields.io/badge/License-@MIT-yellow.svg?style=flat)

> 一款轻量级，开源后台快速开发脚手架

## 项目计划（开发阶段）
|  任务/季度  | 2022 Q1 | 2022 Q2 | 2022 Q3 | 2022 Q4 |
|    ----    |   ----  |   ----  |   ----  |   ----  |
| 1 | 前后分离 | 国际化支持 | x | x |
| 2 | 基础功能重构 | 基础版本发布 | x | x |

## 项目简介
####vuePoem是一款轻量级，开源后台快速开发脚手架
####基于vue + phppoem框架组合开发
####如果您同时熟悉vue和php，可以以最短时间利用本脚手架开发项目

## 项目特性
- vue + php前后端分离
- 命令行工具（一键启动开发模式、一键打包部署模式）
- 开发即上手，0学习成本

## 项目环境（推荐该版本及以上）
- PHP 7.4+
- Node v17.7.2 (npm v8.5.2) +
- MariaDB / Mysql

## 快速了解
前端依赖安装
```shell
cd vuePoem/app/admin/vue && npm i
```

填写配置
编辑全剧配置文件，填写或修改vue、php、mysql相关项
```shell
cd vuePoem/global.cnf && vim global.cnf
```

开发模式（webpack开发服务器+php内建服务器一键启动）
```shell
cd vuePoem && chmod 777 vuePoem
./vuePoem dev
#运行完成后进入`http://127.0.0.1:7777` 用户`admin`密码`123456`
```

生产模式（假设您当前在本地开发完成需要部署在生产环境服务器）
```shell
# 1.打包
cd vuePoem
./vuePoem prod

# 2.vuePoem/public下会有打包后的静态资源
ls vuePoem/public

# 3.配置web服务器（例如Nginx）根目录为vuePoem/public，以下是配置参考
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
