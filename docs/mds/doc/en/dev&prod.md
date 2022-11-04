---
sidebarDepth: 3
---
# Dev startup & Prod deployment
Development startup and production deployment

This chapter will introduce you to:
- Basic part
- Local development, how to develop locally, using CLI auxiliary scripts or manually start the development environment
- Production deployment, how to deploy the project to the production server after secondary development

## Based on some

### Clone items to local
Let's say you have a `~/desktop/` directory and clone it to the Vuepoem project directory
```shell
cd ~/desktop/ && git clone https://github.com/fsd-lsh/vuepoem.git && cd vuepoem
# cd ~/desktop/ && git clone https://gitee.com/fsd-lsh/vuepoem.git && cd vuepoem
```
### Edit the global configuration file
Edit in the `vuePoem/.env` file, the following demonstration is the default configuration for local development. Modify the Vue, PHP, and Mysql configurations according to your actual situation.
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

## Local development

### CLI script assisted startup
To start the development service using the automated helper script, make sure you execute the following command in the `vuepoem` directory. The `./vuePoem mysql` command automatically creates a database for you, provided that `.env` is configured correctly. The `./vuePoem dev` command starts the front-end development service (automatic installation dependency and webpack-dev-server) and back-end development service (PHP built-in server provides API for the front-end). [More](/doc/ cliscript.html#help)
```shell
./vuePoem mysql && ./vuePoem dev
```

### Start manually
If the CLI script has been used, skip the following three steps

#### 1. Access the `./vue/` directory of the front-end project and install the front-end dependency (you are not advised to move this directory).
```shell
cd ./vue && npm i
```
#### 2. Enter the static entry directory `public` of PHPPOEM framework and start the back-end service using the PHP built-in server
```shell
cd ./server/public && php -S 127.0.0.1:8899 index.php
```

#### 3.Starting Front-end Services
```shell
cd ./vue && npm run dev
```

### Start to finish
At this point, you can start the development task by seeing the startup completion information in the command line. Browser access `http://127.0.0.1:7777` can see the front end, the default account password: `admin 123456`.  
The back-end API runs in `http://127.0.0.1:8899`; Normally, you won't debug by visiting this address. VuePoem has acted as a front-end agent for you in the development mode.  
Details refer to: `vuepoem/vue/config/index.js` 19 to 27 rows.

## Production deployment

### CLI script assist packaging
```shell
./vuepoem build
```

### Manual packing
Run the following command directly into the front-end project directory. The static resource is automatically output to the `./server/public` directory
```shell
cd ./vue && npm run build
```

### Deploy vuepoem
Upload the `/server` and `/common` and `/.env` catalog to the server using FTP, version control tool, or other continuous integration tool according to your situation

### Web server configuration
The Nginx example configuration is as follows:
- index Indicates the priority of HTML > PHP
- root Locates under `/server/public/`
- increases the phppoem framework required location rules, the concrete can be reference [More](https://phppoem.com/docs/3.html)
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

## Docker deployment
This section will guide you through how to run VuePoem based on docker, provided that you have installed docker and Docker-compose

### Method 1: Assist the script to run Docker
Run automatically with helper scripts (slow first time)
```shell
./vuepoem docker
```
When run, the script automatically initializes the database to the container and builds the front-end project to`./server/public/`

### Method 2: Manual

#### 1.Build the front
```shell
cd ./vue && npm run build
```
After running, dist is automatically output to`./server/public/`

#### 2.Start the docke
```shell
cd ./docker && docker-compose up
```

#### 3.Import `./vuePoem.sql` to container Mysql
Use Navicat or another tool to connect to the database，(127.0.0.1:3307)
Set the `vuePoem` database character set to utf8mb4 and import `./vuePoem.sql`

#### 4.Open a browser and enter`http://127.0.0.1:8099`
Username：`admin`，Password：`123456`
