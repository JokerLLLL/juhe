<?php
/**
 * Created by PhpStorm.
 * User: jokerl
 * Date: 2019/1/25
 * Time: 13:14
 */

namespace juhe;


class JuheMoneyRateService extends Juhe
{

    //汇率接口
    CONST URL_QUERY = 'http://op.juhe.cn/onebox/exchange/query';
    //开通的key
    CONST KEY = '0d069041ba57aaee911e4db094d518d2x';

    /** 获取 人民币兑换 港币的汇率
     * @return bool|string
     */
    public static function getHongKongRate()
    {
         $r = self::httpCurl(self::URL_QUERY,['key'=>self::KEY],self::TYPE_GET);
         $arr = json_decode($r,true);
         $hongkongmoney = false;
         if($arr['error_code'] == 0) {
             foreach ($arr['result']['list'] as $value) {
                 if($value[0] === '港币') {
                    $hongkongmoney =  $value[5];
                 }
             }
         }
         if($hongkongmoney !== false) {
             return bcdiv(100,$hongkongmoney,5);
         }
        return $hongkongmoney;
    }

}