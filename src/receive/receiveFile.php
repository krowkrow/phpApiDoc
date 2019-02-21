<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2018年4月25日
 * File: receiveFile.php
 * Enconding: UTF-8
 * Using: 接收api文档文件
 */

if (empty($_FILES)) {
    die('no file');
}

if (empty($_FILES['apifile'])) {
    die('not api file');
}

$r = move_uploaded_file($_FILES['apifile']['tmp_name'], './'.basename($_FILES["apifile"]["name"]));

echo $r ? 'success' : 'failed';