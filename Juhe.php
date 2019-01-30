<?php
/**
 * Created by PhpStorm.
 * User: jokerl
 * Date: 2018/12/10
 * Time: 14:28
 */

namespace juhe;


class Juhe
{
    CONST OPEN_ID = 'JH5f859809d8b0701a4a4f96ee7275e881x';
    CONST TYPE_POST = 'POST';
    CONST TYPE_GET = 'GET';


    /** 连接访问
     * @param $url
     * @param string $type
     * @param array $arr
     * @param array $headers
     * @return bool|mixed
     */
    public static function httpCurl($url, $arr = [], $type = self::TYPE_GET, $headers = [])
    {
        //get 请求附带参数
        if($type == self::TYPE_GET && $arr) {
            $url .= '?'.http_build_query($arr);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);      //设置超时时间 30s
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设置参数 成功返回内容 失败返回false
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //json头
        //$headers[] = 'Content-Type: application/json; charset=utf-8';
        if(!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($type == self::TYPE_POST) {
            $json = json_encode($arr);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);//post数据 json或array
        }
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);
        return $output;
    }

}