<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2018年4月20日
 * File: OutHtml.php
 * Enconding: UTF-8
 * Using:生成html文件,对应styles/html 样式
 */
namespace out;

class OutHtml implements OutImp
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
       // $this->outHead();

        if (isset($arrApiData['info'])) {
            $this->outProjInfo($arrApiData['info']);
        }

//        if (isset($arrApiData['groups'])) {
//            foreach ($arrApiData['groups'] as $k => $arrGroup) {
//                $this->outGroup($arrGroup, $k);
//            }
//            $this->outGuild($arrApiData['groups']);
//        }
//
//        $this->outFoot();

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

        <h3 id="post-{$id}" class="login hover_bg_f5">{$arrApi['method']} - {$arrApi['name']} - {$arrApi['api']}</h3>
			<div id="login_post">
			<p><strong id="link_{$id}">{$arrApi['api']}</strong> {$strAuth} <button class="link_{$id}">复制连接</button></p>
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
            echo '<p class="desc">无</p>';
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
			<pre class="language-markup">
{$strJosn['src']}</pre>
            <button class="pure{$id}">复制纯净JSON</button> <p class="appJson" style="display:inline">纯净json</p>
            <pre class="language-markup" id="pure{$id}" style="display:none">
{$strJosn['new']}</pre>

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
            <tr {$bg}>
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

			<div id="box1"></div>
		</div>
		<!-- 目录 -->
		<div class="content_box">
					<ul>

EOL;

        foreach ($arrGroups as $k => $arrGroup) {
            if (empty($arrGroup['apis'])) {
                continue;
            }

            echo <<<EOL
        				<li>
        				    <a id="title-{$k}">{$arrGroup['name']}</a>
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
		</div>

EOL;
    }

    /**
     * 输出页头
     */
    private function outHead()
    {
        echo <<<EOL
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>翼动无疆wiki</title>
	<link rel="stylesheet" href="css/wiki.css">
</head>
<body>
	<div class="wrapper">
		<!-- 头部 -->
		<header class="navbar navbar-default">
			<div class="navbar-header"><a href="/" class="navbar-brand" title="wiki">翼动无疆wiki</a></div>
			<div class="navbar-text">wiki</div>
		</header>
		<!-- 内容区 -->
		<div class="target-fix"></div>
		<div class="content">

EOL;
    }

    /**
     * 输出页尾
     */
    private function outFoot()
    {

        echo <<<EOL

	<script src="js/jquery-3.3.1.js"></script>
	<script src="js/clipboard.min.js"></script>
	<script src="js/app.js"></script>
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