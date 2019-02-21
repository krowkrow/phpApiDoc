<?php
/**
 * User:liufeihong(3427652472@qq.com)
 * Date: 2018/3/30
 * File:Swag.php
 * Using: 提取项目注释生成文说明文档
 */
namespace main;

use out\OutMarkdown;
use out\OutHtmlNew;

class Swag
{

    // 操作目录
    public $path;

    // 打印目录
    public $dest = '';

    // 默认文件打印目录
    private $proj_document_name = 'result_document.json';

    // 项目信息
    private $prog_des = array();

    // 分组信息
    private $des_group = array();

    // api信息
    private $des_api = array();

    // 当前操作的文件
    private $cur_file = '';

    // 当前类的group的code
    private $cur_group_code = '';

    // 是否有解析错误
    private $error_flag = 0;

    // 存放组,项目,api的必须值
    private $proDoc_must_keys = [];

    private $group_must_keys = array(
        'code'
    );

    private $api_must_keys = array(
        'api',
        'name',
        'return'
    );

    // 分别存放组，项目，api的默认值
    private $group_default_param = array(
        'code' => '',
        'name' => '',
        'order' => '1'
    );

    private $proDoc_default_param = array(
        'auth' => '未知',
        'date' => '',
        'testUrl' => ' 未知',
        'version' => 'v1.0',
        'title' => '未知',
        'desc' => '',
        'input-type' => 'application/x-www-form-urlencoded',
        'output-type' => 'application/json'
    );

    private $api_default_param = array(
        'api' => '',
        'name' => '',
        'desc' => '',
        // 默认为当前类的group的code，如果code不存在，则新建一个group，code为该处的值
        'group' => '',
        'method' => 'POST',
        'auth' => '',
        'return' => ''

    );

    public function __construct($path, $dest = '')
    {
        if (! $path) {
            die('no source dir');
        }

        $this->path = $path;
        $this->dest = $dest ? $dest : $this->proj_document_name;
        $this->proDoc_default_param['date'] = date('Y-m-d H:i:s');
    }

    /**
     * 匹配项目
     */
    private function expProg($str)
    {
        $preg = "/@proDoc(.*?)\*\//si";
        $prog = '';
        $g = preg_match_all($preg, $str, $mathces);
        if ($g && ! empty($mathces[1])) {
            $prog = $mathces[1][count($mathces[1]) - 1];
        }
        return $prog;
    }

    /**
     * 匹配分组
     */
    private function expGroup($str)
    {
        $preg = "/@GroupDoc(.*?)\*\//si";
        $prog = '';
        $g = preg_match_all($preg, $str, $mathces);
        if ($g && isset($mathces[1]) && ! empty($mathces[1])) {
            $prog = $mathces[1];
        }
        return $prog;
    }

    /**
     * 匹配api
     */
    private function expApi($str)
    {
        $preg = "/@apiDoc(.*?)\*\//si";
        $prog = '';
        $g = preg_match_all($preg, $str, $mathces);
        if ($g && isset($mathces[1]) && ! empty($mathces[1])) {
            $prog = $mathces[1];
        }
        return $prog;
    }

    /**
     * 遍历目录
     */
    private function myDir($dir)
    {
        $handle = opendir($dir);
        if (! $handle) {
            return;
        }

        $files = array();
        while ($file = readdir($handle)) {
            if ($file == ".." && $file == ".") {
                continue;
            }

            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                $files[$file] = $this->myDir($dir . DIRECTORY_SEPARATOR . $file);
            } elseif (! $this->isPhpFile($file)) {
                continue;
            } else {
                yield $dir . DIRECTORY_SEPARATOR . $file;
            }
        }

        closedir($handle);
    }

    /**
     * 判断是否是php
     */
    private function isPhpFile($file)
    {
        if ('.php' == strtolower(strrchr($file, '.'))) {
            return true;
        }
    }

    /**
     * 获取文件内容
     */
    private function getStr($file)
    {
        if (file_exists($file)) {
            $this->cur_file = $file;
            $file = file_get_contents($file);
            return $file;
        }
    }

    /**
     * 项目返回字符串,接口和分组返回数组
     */
    private function getDesInfo($file)
    {
        $str = $this->getStr($file);
        $cur_prog = $this->expProg($str);
        if ($cur_prog) {
            $return_data = $this->dataCheckout($cur_prog);
            if (isset($return_data['data'])) {
                $this->prog_des = $return_data['data'];
                if (isset($return_data['data']['title']) && $return_data['data']['title']) {
                    $this->proj_document_name = $return_data['data']['title'] . '' . $this->proj_document_name;
                }
            }
        }
        $group_arr = $this->expGroup($str);
        if (! empty($group_arr)) {
            for ($i = 0; $i < count($group_arr); $i ++) {
                $group_str = $this->dataCheckout($group_arr[$i], 2);
                if (isset($group_str['data']) && ! empty($group_str['data'])) {
                    if (isset($group_str['code'])) {
                        $this->cur_group_code = $cur_code = $group_str['code'];
                        $this->des_group[$cur_code]['info'] = $group_str['data'];
                    }
                }
            }
        }
        $api_arr = $this->expApi($str);
        if (! empty($api_arr)) {
            for ($i = 0; $i < count($api_arr); $i ++) {
                $tmp_api = $this->dataCheckout($api_arr[$i], 3);
                if (isset($tmp_api['data']) && ! empty($tmp_api['data'])) {
                    if (isset($tmp_api['code'])) {
                        $cur_code = $tmp_api['code'];
                        $this->des_group[$cur_code]['api'][] = $tmp_api['data'];
                    }
                }
            }
        }
    }

    /**
     * 外部调用接口
     */
    public function run($type = '')
    {
        if (is_dir($this->path)) {
            foreach ($this->myDir($this->path) as $value) {
                if ($value) {
                    $this->getDesInfo($value);
                }
            }
        } elseif (is_file($this->path)) {
            $this->getDesInfo($this->path);
        }

        if (empty($this->des_group) || $this->error_flag) {
            die(PHP_EOL . '有错误，或者分组未空' . PHP_EOL);
            return;
        }

        $result_arr['info'] = $this->prog_des;
        $this->des_group = $this->groupFormat($this->des_group);
        $result_arr['groups'] = &$this->des_group;

        //group排序
        usort($this->des_group, function ($a, $b) {
            return $a['order'] > $b['order'];
        });

        switch ($type) {
            case 'markdown':
                $outOb = new OutMarkdown();
                $outOb->setOutPath($this->dest);
                $outOb->out($result_arr);
                break;
            case 'html':
                $outOb = new OutHtmlNew();
                $outOb->setOutPath($this->dest);
                $outOb->out($result_arr);
                break;
            case '':
                return $result_arr;
                break;
            default:
                $myfile = fopen($this->dest, "w") or die("Unable to open file!");
                $result = fwrite($myfile, json_encode($result_arr));
                fclose($myfile);
                if ($result) {
                    echo "成功生成文档{$this->dest}";
                } else {
                    echo '文档生成失败，请重试';
                }
                break;
        }
    }

    /**
     * 匹配内容二次详细提取并进行信息校对1:项目2：分组3:接口
     */
    public function dataCheckout($str, $type = 1)
    {
        $return_new_arr = array();
        $cur_default_array = array();
        $cur_must_keys = array();
        // group和api用来作标记
        $code_name = '';
        $no_check = 0;
        switch ($type) {
            case 1:
                $cur_default_array = $this->proDoc_default_param;
                $cur_must_keys = $this->proDoc_must_keys;
                break;
            case 2:
                $cur_default_array = $this->group_default_param;
                $cur_must_keys = $this->group_must_keys;
                $code_name = 'code';
                break;
            default:
                $cur_default_array = $this->api_default_param;
                $cur_must_keys = $this->api_must_keys;
                $code_name = 'group';
                break;
        }

        $str = str_replace("\r", '', trim($str));
        $list = explode("\n", $str);
        $list = array_filter($list);

        $new_arr = [];
        $ind = 0;
        foreach ($list as $line) {
            $line = trim($line);
            if (empty($line)) {
                $new_arr[$ind] .= $line . PHP_EOL;
                continue;
            }

            $line = ltrim($line, '*');
            $line = trim($line);
            if (substr($line, 0, 1) == '@') {
                ++$ind;
                $new_arr[$ind] = "";
            }
            $new_arr[$ind] .= ltrim($line, '@') . PHP_EOL;
        }

        $new_arr = array_filter($new_arr);
        if (!$new_arr) {
            return $return_new_arr;
        }

        $return_arr_key = array();
        $param_tmp_arr = array();
        foreach ($new_arr as $cur_val) {
            $cur_val = trim($cur_val);
            if (!$cur_val) {
                continue;
            }

            $cur_key = '';
            $cur_arr = explode(' ', $cur_val, 2);
            $cur_arr = array_filter($cur_arr);
            if (! $cur_arr[0]) {
                continue;
            }
            $cur_key = trim($cur_arr[0]);
            $cur_val = isset($cur_arr[1]) ? trim($cur_arr[1]) : '';
            // 默认值
            $cur_val = $cur_val ? $cur_val : (isset($cur_default_array[$cur_key]) ? $cur_default_array[$cur_key] : '');
            if (in_array($cur_key, $cur_must_keys) && ! $cur_val) {
                $this->errorLog($cur_key, $str, 1);
                $no_check = 1;
                continue;
            }
            // param单独成数组//没有param的情况不可能发生
            if ($type == 3 && 'param' == $cur_key) {
                $cur_val_tmp_arr = array();
                if (! empty($cur_val)) {
                    $cur_val_tmp_arr = $this->paramToArr($cur_val);
                    if (! empty($cur_val_tmp_arr)) {
                        @$return_new_arr['data'][$cur_key][] = $cur_val_tmp_arr;
                    }
                }
            } else {
                @$return_new_arr['data'][$cur_key] = $cur_val;
            }

            if ($code_name && $code_name == $cur_key) {
                $return_new_arr['code'] = $cur_val;
            }
            $return_arr_key[] = $cur_key;
        }
        // print_r($return_new_arr);exit;
        // 开始对提炼出的内容进行判断

        if (! empty($cur_must_keys) && ! empty($return_arr_key) && ! $no_check) {
            $arr_diff = array_diff($cur_must_keys, $return_arr_key);
            if (! empty($arr_diff)) {
                $this->errorLog($arr_diff, $str);
            }
        }
        if ($no_check || $this->error_flag) {
            return;
        }

        $return_new_arr['data'] = $this->filledElement($return_new_arr['data'], $cur_default_array);
        // 对新数组再次进行默认值补充
        if (! empty($return_new_arr['data']) && $type > 1) {
            if ($type == 2) {
                if (isset($return_new_arr['data']['name']) && isset($return_new_arr['data']['code']) && empty($return_new_arr['data']['name'])) {
                    $return_new_arr['data']['name'] = $return_new_arr['data']['code'];
                }
            } elseif ($type == 3) {
                if (isset($return_new_arr['data']['name']) && isset($return_new_arr['data']['desc']) && empty($return_new_arr['data']['desc'])) {
                    $return_new_arr['data']['desc'] = $return_new_arr['data']['name'];
                }
                if (isset($return_new_arr['data']['group']) && empty($return_new_arr['data']['group'])) {
                    if ($this->cur_group_code) {
                        $return_new_arr['order'] = 1;
                        $return_new_arr['name'] = $return_new_arr['code'] = $return_new_arr['data']['group'] = $this->cur_group_code;
                    } else {
                        // 重建group
                        $return_new_arr['order'] = 1;
                        $this->cur_group_code = $return_new_arr['name'] = $return_new_arr['code'] = $return_new_arr['data']['group'] = time();
                    }
                }
                if (isset($return_new_arr['data']['return']) && $return_new_arr['data']['return']) {
                    // 暂时用这种方式处理
                    $return_new_arr['data']['return'] = str_replace('json', '', $return_new_arr['data']['return']);
                }
            }
        }
        // print_r($return_new_arr);exit;
        return $return_new_arr;
    }

    /**
     * 重置，没有的参数用默认数组来填充
     */
    private function filledElement($arr, $filled_arr)
    {
        // print_r($arr);print_r($filled_arr);
        if (empty($arr) || empty($filled_arr)) {
            return;
        }
        foreach ($filled_arr as $key => $value) {
            if (! isset($arr[$key])) {
                $arr[$key] = $value;
            }
        }
        // print_r($arr);exit;
        return $arr;
    }

    /**
     * 单独处理param
     */
    private function paramToArr($arr = array())
    {
        if (empty($arr)) {
            return;
        }
        $arr = explode(' ', $arr);
        $arr = array_filter($arr, function($v){ return strlen(trim($v)) > 0;});

        //先确定前两项，如果还有，则检测req/noreq的标志，如果未检测到，则全是desc的部分；
        $paramInfo = [
            'name' => array_shift($arr),
            'type' => '-',
            'desc' => '',
            'require' => true,
            'default' => '',
        ];
        if (empty($arr)) {
            return $paramInfo;
        }
        $paramInfo['type'] = array_shift($arr);

        //确定desc
        foreach ($arr as $k => $v) {
            if (!in_array(trim($v), ['req', 'noreq'])) {
                $paramInfo['desc'] = $paramInfo['desc'] . $v;
            } else {
                $paramInfo['require'] = ($v == 'req');
                if (($k + 1) < count($arr)) {
                    $paramInfo['default'] = $arr[$k + 1];
                }
                break;
            }
        }

        return $paramInfo;
    }

    /**
     * 信息格式化
     */
    private function groupFormat($data)
    {
        $return_arr = array();
        foreach ($data as $key => $value) {
            if (isset($value['info'])) {
                $value['info']['apis'] = isset($value['api']) && ! empty($value['api']) ? $value['api'] : '';
                $return_arr[] = $value['info'];
            } elseif (isset($value['api']) && ! empty($value['api'])) {
                $value['info'] = array(
                    'code' => $key,
                    'name' => $key,
                    'order' => 1,
                    'apis' => $value['api']
                );
                $return_arr[] = $value['info'];
            }
        }
        return $return_arr;
    }

    /**
     * 记录错误日志
     */
    private function errorLog($absent_param, $dest_param, $type = '')
    {
        $this->error_flag = 1;
        if ($type) {
            $str = "Error1:{$this->cur_file} need to add  {$absent_param} at {$dest_param}";
            echo $str . PHP_EOL;
        } else {
            $str = "Error:File:{$this->cur_file} need to add  " . implode(',', $absent_param) . " at {$dest_param}";
            echo $str . PHP_EOL;
        }
    }
}
