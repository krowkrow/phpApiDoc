<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2018年4月9日
 * File: index.php
 * Enconding: UTF-8
 * Using:命令行执行，格式如下：
 *       php index.php example -o ./tt.md
 * example是需要解析的文件目录路径，-o是可选参数，是输出文件路径
 */
set_time_limit(0);
if (PHP_SAPI != 'cli') {
    die('please run it with php-cli');
}

$get_data=$argv;
if (!isset($argv[1])||empty($argv[1])) {
    die('请录入项目地址');
}
$path_dest=isset($argv[3]) ? trim($argv[3]) : date('YmdHis');
$path_source=trim($argv[1]);

spl_autoload_register(function ($classname) {
    $classname = str_replace('\\', '/', $classname);
    $file = sprintf('%s/src/%s.php', __DIR__, $classname);
    if (file_exists($file)) {
        require_once $file;
    } else {
        exit('not find file '.$file);
    }
});

$swig=new main\Swag($path_source, $path_dest);
$data = $swig->run('html');

//$request = new request\Request();
//$request->upFile('http://192.168.2.250:81/receiveFile.php', $path_dest);
