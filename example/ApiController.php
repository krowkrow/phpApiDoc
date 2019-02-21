<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2017年12月22日
 * File: ApiController.php
 * Enconding: UTF-8
 * Using: 基础接口类
 */
namespace app\modules\v1\controllers;

/**
 * @proDoc
 * @auth 庞凯
 * @date 2018-03-09
 * @testUrl http://192.168.1.188:8021
 * @version v1.0
 * @title 车贷在线
 * @desc 车贷在线APP的api部分
 * @input-type application/x-www-form-urlencoded
 * @output-type application/json
 */
class  ApiController
{

	/**
	 * 获得参数
	 * @param unknown $strKey
	 * @param string $isRequired
	 * @param unknown $mixDefault
	 */
	protected function getParam($strKey, $isRequired = true, $mixDefault = null, $strName = null)
	{

	}

    /**
     * 返回结构
     * @param $intcode 错误码
     * @param $data 返回数据
     */
	protected function response($intcode, $arrData = null, $errorParam = null)
    {

    }

}
