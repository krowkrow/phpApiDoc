<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2018年4月9日
 * File: OutImp.php
 * Enconding: UTF-8
 * Using:输出格式的接口文件
 */
namespace out;

interface OutImp
{

    /**
     * 设置输出路径
     * @param string $strPath
     */
    public function setOutPath($strPath);

    /**
     * 输出到目标文件
     * @param array $arrApiData api数据
     */
    public function out($arrApiData);
}
