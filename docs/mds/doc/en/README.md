---
sidebarDepth: 3
---
# introduce
VuePoem is a lightweight, open source scaffolding for rapid back-end development. Combine PHP7, Vue2, ElementUI, Vite, Phppoem, Nginx, Mysql using the mainstream stable technology stack.  
Precise for VUE + PHP full stack developers to use

## Environmental requirements
#### back-end
|  Runtime environment  | Required version | Recommended version |
|    ----    |   ----  |   ----  |
| PHP | >= 7.0 | 7.4 |
| Mysql | >= 5.7 | 5.7 |
#### front end
|  Runtime environment  | Required version | Recommended version |
|    ----    |   ----  |   ----  |
| Node | >=6.0.0 | 14.20.0 |
| Npm | >=3.0.0 | 6.14.17 |

## features
- vue + phppoem separation
- Command-line tools (one-click development mode, one-click packaged deployment mode)
- Development is hands-on, close to zero learning cost
- System ownership
  - Role, menu, user base permission model,
  - Log and system monitoring
  - Customize themes
  - Multi-language support
![show-0](/1.png)
![show-1](/2.png)
![show-2](/3.png)

#### directory structure
```text
.
├── LICENSE                             // MIT Open Source License
├── README.MD
├── README_CN.MD
├── .env                                // Global config
├── common                              // Front and rear end universal
│       ├── lang
│       ├── other
│       └── theme
├── server
│       ├── app                         // application directory
│       │   ├── admin                   // background directory
│       │   │   └── controller          // API controller
│       │   ├── config.php              // Php config
│       │   ├── function.php            // VuePoem function
│       │   ├── home
│       │   ├── route.php
│       │   └── service
│       ├── phppoem                     // phppoem framework dir
│       └── public                      // wwwroot dir
│           └── index.php               // Entrance
├── vue
│       ├── build
│       ├── config
│       ├── index.html
│       ├── package.json
│       ├── src
│       ├── static
│       └── store
├── vuePoem                             // helper script ./vuePoem [option] 
└── vuePoem.sql                         // mysql tables
```
