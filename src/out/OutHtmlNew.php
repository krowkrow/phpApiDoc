<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2018年8月6日
 * File: OutHtmlNew.php
 * Enconding: UTF-8
 * Using:生成html文件，对应styles/htmlnew 样式
 */
namespace out;

class OutHtmlNew implements OutImp
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

        $projName = '';
        if (isset($arrApiData['info']) && isset($arrApiData['info']['title'])) {
        	$projName = $arrApiData['info']['title'];
        }

        $this->outHead($projName);

        if (isset($arrApiData['info'])) {
            $this->outProjInfo($arrApiData['info']);
        }

        if (isset($arrApiData['groups'])) {
            foreach ($arrApiData['groups'] as $k => $arrGroup) {
                $this->outGroup($arrGroup, $k);
            }
            $this->outGuild($arrApiData['groups']);
        }

        $this->outFoot();

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
    private function outGroup($arrGroup, $ind)
    {
        if (empty($arrGroup['apis'])) {
            return ;
        }

        echo <<<EOL

			<h2 id="title-{$ind}" class="staff hover_bg_f5">{$arrGroup['name']}<span class="arrow" id="arrow1"></span></h2>
			<div id="staff_msg">

EOL;

        foreach ($arrGroup['apis'] as $arrApi) {
            $this->outApi($arrApi);
        }

        echo <<<EOL

			</div>

EOL;
    }

    /**
     * 输出API信息
     * @param unknown $arrApi
     */
    private function outApi($arrApi)
    {
        $id = $this->getApiId($arrApi['api']);
        $strAuth = empty($arrApi['auth']) ? '' : " -- {$arrApi['auth']}";
        echo <<<EOL
            <h3 id="post-{$id}" class="login hover_bg_f5 active">{$arrApi['method']} - {$arrApi['name']} - {$arrApi['api']}</h3>
			<div id="login_post">
			<p><strong id="link_{$id}">{$arrApi['api']}</strong> {$strAuth} <button class="link_{$id} fa fa-copy">复制连接</button></p>
			<p class="desc">{$arrApi['desc']}</p>
			<p><strong>输入参数</strong></p>
EOL;

        if (!empty($arrApi['param'])) {
            $this->outParamHeader();
            foreach ($arrApi['param'] as $k => $arrParam) {
                $this->outParam($arrParam, $k%2);
            }
            $this->outParamFooter();
        } else {
            echo '<p class="desc">无参数</p>';
        }

        $this->outResponse($arrApi['return'], $id);

        echo <<<EOL

            </div>

EOL;
    }

    /**
     * 输出返回值
     * @param unknown $strRes
     */
    private function outResponse($strRes, $id='')
    {
        $strJosn = ParseUtil::filterJsonStr($strRes);
        echo <<<EOL

			<p><strong>返回</strong></p>
			<pre class="language-markup">{$strJosn['src']}</pre>
            <p class="appJson"><strong>展开纯净json</strong><button class="puremanager_editpwd fa fa-copy"> 复制纯净JSON</button></p>
            <pre class="language-markup" id="pure{$id}">{$strJosn['new']}</pre>

EOL;
    }

    /**
     * 输出参数头
     */
    private function outParamHeader()
    {
        echo <<<EOL

			<table class="parameter-table">
				<thead>
					<tr>
						<th>参数</th>
						<th>类型</th>
						<th>必须</th>
						<th>默认值</th>
						<th>描述</th>
					</tr>
				</thead>
				<tbody>

EOL;
    }

    /**
     * 输入参数
     * @param unknown $arrParams
     */
    private function outParam($arrParams, $isBg)
    {
        if (empty($arrParams)) {
            return ;
        }

        $req = $arrParams['require'] ? '<font color="red">是</font>' : '否';
        $bg = $isBg == 1 ? ' class="trb"' : '';
        echo <<<EOL
                    <tr>
						<td>{$arrParams['name']}</td>
        				<td>{$arrParams['type']}</td>
        				<td>{$req}</td>
        				<td>{$arrParams['default']}</td>
        				<td>{$arrParams['desc']}</td>
        			</tr>

EOL;
    }

    private function outParamFooter()
    {
        echo <<<EOL
                </tbody>
			</table>

EOL;
    }

    /**
     * 输出项目信息
     * @param array $arrPorj
     */
    private function outProjInfo($arrPorj)
    {
        date_default_timezone_set('Asia/Shanghai');
        $now = date('Y-m-d H:i:s');
        echo <<<EOL
			<h1 id="title">{$arrPorj['title']}</h1>
			<p>作者：{$arrPorj['auth']}，{$arrPorj['date']}</p>
			<p class="desc">{$arrPorj['desc']}</p>
			<p>版本： {$arrPorj['version']}</p>
			<p>测试地址： {$arrPorj['testUrl']}</p>
			<p>输入格式： {$arrPorj['input-type']}</p>
			<p>输出格式： {$arrPorj['output-type']}</p>
			<p>生成时间： {$now}</p>

EOL;
    }

    /**
     * 输出引导
     */
    private function outGuild($arrGroups)
    {
        echo <<<EOL

			<div class="rgt_end">this end, no more, god love you</div>
		</div>
		<!-- 目录 -->
		<ul class="content_box">

EOL;

        foreach ($arrGroups as $k => $arrGroup) {
            if (empty($arrGroup['apis'])) {
                continue;
            }

            echo <<<EOL
            <li>
            <a id="title-{$k}"><b>{$arrGroup['name']}</b></a>
            	<ul>
EOL;

            foreach ($arrGroup['apis'] as $arrApi) {
                $id = $this->getApiId($arrApi['api']);
                echo <<<EOL

				      <li><a id="post-{$id}">{$arrApi['name']}</a></li>
EOL;
            }

            echo <<<EOL

            	</ul>
            </li>

EOL;
        }

        echo <<<EOL
		</ul>

EOL;
    }

    /**
     * 输出页头
     */
    private function outHead($projName)
    {
        echo <<<EOL
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{$projName}</title>
	<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="styles/htmlnew/css/common.css">
</head>
<body>
	<div class="wrapper" id="app">
		<!-- 头部 -->
		<header class="navbar navbar-default">
			<div class="navbar-header"><a href="/" class="navbar-brand" title="wiki">{$projName}</a></div>
			<div class="navbar-text">wiki</div>
			<i class="expandall fa fa-plus-square" title="展开全部"></i>
			<i class="shrinkall fa fa-minus-square" title="折叠全部"></i>
		</header>
        <!-- 内容区 -->
		<div class="content">

EOL;
    }

    /**
     * 输出页尾
     */
    private function outFoot()
    {

        echo <<<EOL
		<!-- 提示框 -->
		<div class="showpanel">
			<p><i class="fa fa-info-circle"></i>您的信息已复制</p>
		</div>
	</div>
<script src="styles/htmlnew/js/jquery-3.3.1.js"></script>
<script src="styles/htmlnew/js/clipboard.min.js"></script>
<script src="styles/htmlnew/js/app.js"></script>
</body>
</html>

EOL;

    }

    /**
     * 获得api的Id
     * @param unknown $strApi
     * @return mixed
     */
    private function getApiId($strApi)
    {
        $strApi = trim($strApi, '/');
        return str_replace('/', '_', $strApi);
    }
}