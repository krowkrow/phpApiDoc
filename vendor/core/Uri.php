<?php

/**
 * Created by PhpStorm.
 * User: qinsan
 * Date: 2018/3/26
 * Time: 13:28
 */

namespace core;

class Uri
{
    public function test($filename)
    {
        if (function_exists('opcache_get_status') && function_exists('opcache_get_configuration')) {
            if (empty($GLOBALS['swagger_opcache_warning'])) {
                $GLOBALS['swagger_opcache_warning'] = true;
                $status = opcache_get_status();
                $config = opcache_get_configuration();
                if ($status['opcache_enabled'] && $config['directives']['opcache.save_comments'] == false) {
                    Logger::warning("php.ini \"opcache.save_comments = 0\" interferes with extracting annotations.\n[LINK] http://php.net/manual/en/opcache.configuration.php#ini.opcache.save-comments");
                }
            }
        }
        $tokens = token_get_all(file_get_contents($filename));

        $result = $this->fromTokens($tokens, new Context(['filename' => $filename]));
        // var_dump($result);
    }

    protected function fromTokens($tokens, $parseContext)
    {
        $result = [];
        foreach ($tokens as $token) {
            if (is_array($token) and $token['0'] == 378) {
                $pos = strpos($token[1], '@SWG\\');
                if ($pos) {
                    $comment = preg_replace_callback('/^[\t ]*\*[\t ]+/m', function ($match) {
                        // Replace leading tabs with spaces.
                        // Workaround for http://www.doctrine-project.org/jira/browse/DCOM-255
                        return str_replace("\t", ' ', $match[0]);
                    }, $token[1]);
                    $datas = explode('<br />', nl2br($comment));

                    $_result = [];
                    foreach ($datas as $data) {
                        $temp = strpos($data, '@');
                        if ($temp !== false and substr($data, $temp + 1, 3) != 'SWG') {
                            $line = substr(rtrim(rtrim($data, ' '), ','), $temp);
                            $line_array = explode('=', $line);
                            $_result[$line_array[0]] = $line_array[1];
                        }
                    }
                    $result[] = $_result;
                }
            }
        }
        var_dump($result);
        return $result;
    }
}