<?php
/**
 * Created by PhpStorm.
 * User: jokerl
 * Date: 2018/12/10
 * Time: 19:35
 */

namespace juhe;


class JuhePhoneService extends Juhe
{
     //下单地址
     CONST KEY = '04b5407e7e3b7d6dbc11543006acb58dx';// '04b5407e7e3b7d6dbc11543006acb58d'
     CONST URL_ORDER = 'http://op.juhe.cn/ofpay/mobile/onlineorder'; //下单接口
     CONST URL_CHECK = 'http://op.juhe.cn/ofpay/mobile/telcheck';    //检查接口

    /** 聚合下订单
     *
     * $orderPhone = [
        'phoneno',
        'cardnum',
        'orderid'
      ];
     *
     * @param $orderPhone
     * @return bool
     */
     public static function createOrder($orderPhone)
     {
          $post = [
               'phoneno'=>$orderPhone['phoneno'],
               'cardnum'=>$orderPhone['cardnum'],
               'orderid'=>$orderPhone['orderid'],
               'key'=>self::KEY,
               'sign'=>self::sign($orderPhone)
          ];

          $r = self::httpCurl(self::URL_ORDER,$post);
           $requestBack = json_decode($r,true);
           if($requestBack['error_code'] == 0) {
               if($requestBack['result']['game_state'] == 0) {
                   return 0; //充值中
               }
               if($requestBack['result']['game_state'] == 1) {
                   return 1; //充值成功
               }
           }
         //充值失败
         return false;
     }

    /** 签名
     * @param $attributes
     * @return string
     */
    public static function sign($attributes)
     {
         //是 string	 校验值，md5(OpenID+key+phoneno+cardnum+orderid)，OpenID在个人中心查询
         return md5(self::OPEN_ID.self::KEY.$attributes['phoneno'].$attributes['cardnum'].$attributes['orderid']);
     }


    /** 验证是否能充值
     $orderPhone = [
        'phoneno',
        'cardnum',
        'orderid'
     ];
     * @param $orderPhone
     * @return bool
     */
    public static function checkCanRechargePhone($orderPhone)
     {
          $params = [
              'phoneno' => $orderPhone['phoneno'],
              'cardnum' => $orderPhone['cardnum'],
              'key' => self::KEY
          ];
          $result = self::httpCurl(self::URL_CHECK,$params);
          $arr = json_decode($result,true);
          return boolval($arr['error_code'] === 0);
     }


    /** 聚合数据回调
     * @param $data
     * @return boolean
     */
    public static function juheCallBack($data)
    {
        $orderid = $data['orderid'];
        $status = $data['sta'];
        $sign = $data['sign'];
        if($status == 1) {
            return 1;
        }elseif ($status == 9) {
            return 9;
        }
        return false;
    }

}