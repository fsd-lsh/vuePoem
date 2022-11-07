---
sidebarDepth: 3
---
# 开发启动及生产部署

本章节将为您介绍：
- 基础部分
- 本地开发，如何在本地开发，使用CLI辅助脚本或者手动启动开发环境
- 生产部署，项目二次开发后如何部署到生产环境服务器

## 基础部分

### clone项目到本地
假设你存放的目录为`~/desktop/`, clone后进入vuepoem项目目录
```shell
cd ~/desktop/ && git clone https://github.com/fsd-lsh/vuepoem.git && cd vuepoem
# cd ~/desktop/ && git clone https://gitee.com/fsd-lsh/vuepoem.git && cd vuepoem
```
### 编辑全局配置文件
编辑位于`vuepoem/.env`文件，以下示范为本地开发默认配置。根据您的实际情况修改Vue、PHP、Mysql配置。
```ini
# Mysql configuration
MYSQL_HOST      = 127.0.0.1
MYSQL_PORT      = 3306
MYSQL_USER      = root
MYSQL_PASS      = 123456
MYSQL_DBNM      = vuePoem
MYSQL_PREFIX    = poem_
MYSQL_CHARSET   = utf8mb4

# Vue configuration
VUE_PROJECT_PATH    = ./vue
VUE_HOST            = 127.0.0.1
VUE_PORT            = 7777

# Php configuration
PHP_PROJECT_PATH    = ./server
PHP_HOST            = 127.0.0.1
PHP_PORT            = 8899


# Common configuration
LANG_PATH       = common/lang
THEME_PATH      = common/theme
```

## 本地开发

### CLI脚本辅助启动
使用自动化辅助脚本来启动开发服务，请确保您在`vuepoem`目录执行以下命令。`./vuePoem mysql`命令会为您自动创建数据库，当然前提是`.env`配置正确。`./vuePoem dev`命令会启动前端开发服务(自动安装依赖及vite)及后端开发服务(php内建服务器为前端提供API)，[关于CLI脚本更多可阅读此处](/doc/cliScript.html#help)
```shell
./vuePoem mysql && ./vuePoem dev
```

### 手动启动
若已使用CLI脚本请跳过以下3个步骤

#### 1.进入前端工程目录`./vue/`目录，安装前端依赖 （此目录不建议移动）。
```shell
cd ./vue && npm i
```
#### 2.进入phppoem框架静态入口目录`public`，使用php内建服务器启动后端服务
```shell
cd ./server/public && php -S 127.0.0.1:8899 index.php
```

#### 3.启动前端服务
```shell
cd ./vue && npm run dev
```

### 启动完成
此时您在命令行看到启动完成信息即可开始开发任务。浏览器访问`http://127.0.0.1:7777`即可看到前端， 默认账号密码：`admin 123456`。后端API则运行在`http://127.0.0.1:8899`；一般情况下不会通过访问此地址调试，VuePoem已经为您在开发模式下做前端代理。详情参考：`vuepoem/vue/vite.config.js` server.proxy配置。

## 生产部署

### CLI脚本辅助打包
```shell
./vuepoem build
```

### 手动打包
直接进入前端工程目录运行如下命令，静态资源会自动输出到`./server/public`目录
```shell
cd ./vue && npm run build
```

### 部署vuepoem
将`/server`、`/common`、`/.env`目录上传至服务器，使用ftp、版本控制工具、其它持续集成工具根据您的情况

### web服务器配置
Nginx示例配置如下：
- index 优先级,html > php
- root 目录配置需要定位到`/server/public/`下
- 增加phppoem框架所需的location规则，具体可[参考](https://phppoem.com/docs/3.html)
```Nginx
server {
    listen       80;
    server_name  prod.vuepoem.force;
    root   /server/public/;
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
        #include        fastcgi-php.conf;
        include        fastcgi_params;
    }
}

```

## Docker部署
此部分将引导您如何基于Docker运行VuePoem，前提您在本地或server已经安装docker及docker-compose

### 方式1，辅助脚本运行Docker
使用辅助脚本自动运行（首次慢）
```shell
./vuepoem docker
```
运行后脚本自动向容器初始化数据库、构建前端项目到`./server/public/`

### 方式2，手动

#### 1.构建前端
```shell
cd ./vue && npm run build
```
运行后会自动向`./server/public/`输出dist

#### 2.启动docker
```shell
cd ./docker && docker-compose up
```

#### 3.导入`./vuePoem.sql`到容器Mysql
使用Navicat或者其它工具连接数据库，(127.0.0.1:3307)  
创建`vuePoem`数据库字符集设置为utf8mb4，并导入`./vuePoem.sql`

#### 4.打开浏览器进入`http://127.0.0.1:8099`
用户名：`admin`，密码：`123456`




