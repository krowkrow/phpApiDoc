<?php

//自动加载类
spl_autoload_register(
    function ($class) {
        $vendorDir = dirname(__FILE__);
        $fileName = $vendorDir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';//替换符号
        if (is_file($fileName)) {
            //判断文件是否存在
            include_once $fileName;
        } else {
            echo $fileName . 'is not exist';
        }
    },
    true,
    false
);
