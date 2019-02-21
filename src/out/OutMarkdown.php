<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2018年4月9日
 * File: OutMarkdown.php
 * Enconding: UTF-8
 * Using: 输出markdown文件
 */
namespace out;

class OutMarkdown implements OutImp
{

    /**
     * 输出目标路径
     */
    protected $_destPath = 'tmpApi.md';

    /**
     * 设置输出文件路径
     * {@inheritDoc}
     * @see \out\OutImp::setOutPath()
     */
    public function setOutPath($strPath)
    {
        $this->_destPath = $strPath;
    }

    /**
     * 输出
     * {@inheritDoc}
     * @see \out\OutImp::out()
     */
    public function out($arrApiData)
    {
        if (empty($arrApiData)) {
            return ;
        }

        ob_start();
        if (isset($arrApiData['info'])) {
            $this->outProjInfo($arrApiData['info']);
        }

        if (isset($arrApiData['groups'])) {
            foreach ($arrApiData['groups'] as $arrGroup) {
                $this->outGroup($arrGroup);
            }
        }

        $info = ob_get_contents();
        ob_clean();

        $fp = fopen($this->_destPath, 'w');
        if (!$fp) {
            die('can not open file: '. $this->_destPath);
        }
        fwrite($fp, $info);
        fclose($fp);
    }

    /**
     * 输出组信息
     */
    private function outGroup($arrGroup)
    {
        echo <<<EOL

## {$arrGroup['name']}

EOL;

        if (empty($arrGroup['apis'])) {
            return ;
        }

        foreach ($arrGroup['apis'] as $arrApi) {
            $this->outApi($arrApi);
        }
    }

    /**
     * 输出API信息
     * @param unknown $arrApi
     */
    private function outApi($arrApi)
    {
        echo <<<EOL

### {$arrApi['name']}-{$arrApi['method']}

作者： {$arrApi['auth']}

{$arrApi['desc']}

** 输入参数 **

EOL;

        if (!empty($arrApi['param'])) {
            $this->outParamHeader();
            foreach ($arrApi['param'] as $arrParam) {
                $this->outParam($arrParam);
            }
        }

        $this->outResponse($arrApi['return']);
    }

    /**
     * 输出返回值
     * @param unknown $strRes
     */
    private function outResponse($strRes)
    {
        echo <<<EOL

** 返回 **

`
{$strRes}
`

EOL;
    }

    /**
     * 输出参数头
     */
    private function outParamHeader()
    {
        echo <<<EOL

| 参数 | 类型 | 是否必须  | 默认值 | 描述 |
| ----- | ----- | ----- | ----- | ----- |

EOL;
    }

    /**
     * 输入参数
     * @param unknown $arrParams
     */
    private function outParam($arrParams)
    {
        if (empty($arrParams)) {
            return ;
        }

        $req = $arrParams['require'] ? '是' : '否';
        echo <<<EOL
| {$arrParams['name']} | {$arrParams['type']} | {$req} | {$arrParams['default']} | {$arrParams['desc']} |

EOL;
    }

    /**
     * 输出项目信息
     * @param array $arrPorj
     */
    private function outProjInfo($arrPorj)
    {
        echo <<<EOL
# {$arrPorj['title']}

作者： {$arrPorj['auth']}  {$arrPorj['date']}

 {$arrPorj['desc']}

版本： {$arrPorj['version']}

测试地址： {$arrPorj['testUrl']}

输入格式： {$arrPorj['input-type']}

输出格式： {$arrPorj['output-type']}

EOL;
    }
}