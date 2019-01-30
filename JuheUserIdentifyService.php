<?php
/**
 * Created by PhpStorm.
 * User: jokerl
 * Date: 2019/1/21
 * Time: 15:44
 */

namespace juhe;


class JuheUserIdentifyService extends Juhe
{
     //身份证验证 不需要验签
     CONST URL_QUERY = 'http://op.juhe.cn/idcard/query';
     //开通的key
     CONST KEY = '72973e2b36d4796332a229dfb8021c64x';


    /** 验证身份证和姓名是否匹配
     * @param $id_card
     * @param $name
     * @return bool
     */
     public static function userCheck($id_card, $name)
     {
            $requset_data = [
                'key'    =>self::KEY,
                'idcard' => $id_card,
                'realname' =>$name
            ];
             $result = self::httpCurl(self::URL_QUERY,$requset_data,self::TYPE_GET);
             $json_info = json_decode($result,true);
             if($json_info && $json_info['error_code'] == 0 && $json_info['result']['res'] == 1) {
                 return true;
             }
             return false;
     }

}