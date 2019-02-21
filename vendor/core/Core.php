<?php
/**
 * Created by PhpStorm.
 * User: qinsan
 * Date: 2018/3/26
 * Time: 11:10
 */

namespace core;


class Core
{
    public static function run($path)
    {
        $filename = 'D:\phpstudy\WWW\phpapidoc\docs\CountryController.php';
        $uri = new Uri();
        $uri->test($filename);
    }
}
