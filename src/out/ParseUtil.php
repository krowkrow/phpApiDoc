<?php
/**
 * User: pangkai(116390143@qq.com)
 * Date: 2018年4月28日
 * File: ParseUtil.php
 * Enconding: UTF-8
 * Using: 解析辅助
 */
namespace out;

class ParseUtil
{
    private static $depth = 0;

    public static function filterJsonStr($jsonStr)
    {
        $jsonStr = str_replace("\r", '', $jsonStr);
        $lineList = explode("\n", $jsonStr);

        //json结构格式化
        $space = "    ";
        self::$depth = 0; //结构深度

        $str = $src = '';
        foreach ($lineList as $line) {
            $line = trim($line, "\n\r\t");
            $line = $srcLine = trim($line);
            if (empty($line)) {
                continue;
            }

            //注释即为描述
            $noteInd = strrpos($line, '//');
            if ($noteInd !== false && substr($line, $noteInd - 1, 1) != ':') {
                $newline = substr($line, 0, $noteInd);
                $newline = trim($newline);

                if ((substr_count($newline, '"') % 2 == 0) && preg_match('#[0-9\",\{\}\[\]]$#', substr($newline, -1))) {
                    $line = $newline;
                }
            }

            if ((substr_count($line, ']') > substr_count($line, '[')) || (substr_count($line, '}') > substr_count($line, '{'))) {
                self::$depth = self::$depth - 1;
            }

            for ($i = self::$depth; $i > 0; $i--) {
                $line = $space . $line;
                $srcLine = $space . $srcLine;
            }
            $str .= $line . PHP_EOL;
            $src .= $srcLine . PHP_EOL;
            if ((substr_count($line, ']') < substr_count($line, '[')) || (substr_count($line, '}') < substr_count($line, '{'))) {
                self::$depth = self::$depth + 1;
            }
        }

        return [
            'src' => $src,
            'new' => $str
        ];
    }

}