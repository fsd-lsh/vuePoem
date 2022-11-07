---
sidebarDepth: 3
---
# 介绍
VuePoem是一款轻量级，开源后台快速开发脚手架。使用主流稳定技术栈组合PHP7、Vue2、ElementUI、Vite、Phppoem、Nginx、Mysql。  
精准适用于希望vue+php全栈开发人员使用

## 环境要求
#### 后端
|  运行环境  | 要求版本 | 推荐版本 |
|    ----    |   ----  |   ----  |
| PHP | >= 7.0 | 7.4 |
| Mysql | >= 5.7 | 5.7 |
#### 前端
|  运行环境  | 要求版本 | 推荐版本 |
|    ----    |   ----  |   ----  |
| Node | >=6.0.0 | 14.20.0 |
| Npm | >=3.0.0 | 6.14.17 |

## 项目特性
- vue + phppoem前后端分离
- 命令行工具（一键启动开发模式、一键打包部署模式）
- 开发即上手，接近0学习成本
- 系统拥有
  - 角色、菜单、用户基础权限模型，
  - 日志、系统监控
  - 自定义主题
  - 多国语支持
![show-0](/1.png)
![show-1](/2.png)
![show-2](/3.png)

#### 目录结构
```text
.
├── LICENSE                             // MIT开源许可
├── README.MD
├── README_CN.MD
├── .env                                // 全局配置文件
├── common                              // 前后端通用目录
│       ├── lang
│       ├── other
│       └── theme
├── server
│       ├── app                         // 应用目录
│       │   ├── admin
│       │   │   └── controller          // API控制器
│       │   ├── config.php              // php配置
│       │   ├── function.php            // VuePoem封装辅助函数
│       │   ├── home
│       │   ├── route.php
│       │   └── service
│       ├── phppoem                     // phppoem框架目录
│       └── public                      // 站点根目录
│           └── index.php               // API入口
├── vue
│       ├── build
│       ├── config
│       ├── index.html
│       ├── package.json
│       ├── src
│       ├── static
│       └── store
├── vuePoem                             // 辅助脚本, ./vuePoem [option] 方式运行
└── vuePoem.sql                         // mysql数据表初始数据
```
