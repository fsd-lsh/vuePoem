<?php
define('APP_PATH', realpath( __DIR__.'/../').'/app/');
define('APP_DEBUG', true);
define('APP_DEMO', false);
define('VUEPOEM_ROOT', realpath(__DIR__.'/../../'));
header("X-VuePoem-Author-By: Force (https://www.easybhu.cn)");
require __DIR__ . '/../phppoem/start.php';
