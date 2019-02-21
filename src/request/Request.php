<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2018年4月25日
 * File: Request.php
 * Enconding: UTF-8
 * Using:
 */

namespace request;

class Request
{
    /**
     * 上传文件
     * @param unknown $strUrl
     * @param unknown $filePath
     */
    public function upFile($strUrl, $filePath)
    {
        if (!file_exists($filePath)) {
            return;
        }

        $curl = curl_init();

        if (class_exists('\CURLFile')) {
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
            $data = array('apifile' => new \CURLFile(realpath($filePath))); //>=5.5
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
            }
            $data = array('apifile' => '@' . realpath($filePath)); //<=5.5
        }

        curl_setopt($curl, CURLOPT_URL, $strUrl);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "TEST");

        $result = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);


        return $result;
    }
}
