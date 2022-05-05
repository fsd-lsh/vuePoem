# AdminPoem2

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
####AdminPoem2是一款轻量级，开源后台快速开发脚手架
####基于vue + phppoem框架组合开发
####如果您同时熟悉vue和php，可以以最短时间利用本脚手架开发项目

## 使用简介
####前端依赖安装
```shell
cd adminPoem2/app/admin/vue && npm i
```
####开发模式（webpack开发服务器+php内建服务器一键启动）
```shell
cd adminPoem2 && ./run.sh
#运行完成后进入`http://127.0.0.1:7777` 用户`admin`密码`123456`
```

####生产模式（假设您当前在本地开发完成需要部署在生产环境服务器）
```shell
# 1.webpack打包
cd adminPoem2/app/admin/vue && npm run build

# 2.此时在adminPoem2/public下会有打包后的静态资源
ls adminPoem2/public

# 3.配置web服务器（例如Nginx）指定目录到adminPoem2/public，设置index.html为第一优先
vim vhost.cnf
```

## 开源许可
[MIT](LICENSE)

## 作者
[Force](https://www.easybhu.cn)
