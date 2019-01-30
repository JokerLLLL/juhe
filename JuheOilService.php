<?php
/**  油卡接口
 * Created by PhpStorm.
 * User: jokerl
 * Date: 2018/12/10
 * Time: 14:26
 */

namespace juhe;


class JuheOilService extends Juhe
{
     CONST KEY = '0b8034a78e45e178ea4799b42faa050ax';
     CONST URL_ORDER = 'http://op.juhe.cn/ofpay/sinopec/onlineorder'; //充值接口
     CONST URL_CHECK = 'http://op.juhe.cn/ofpay/sinopec/ordersta'; //查询接口

    /** 提交订单给聚合数据
     * $order = [
            'proid',
            'cardnum',
            'orderid',
            'game_userid',
            'gasCardTel',
            'gasCardName',
            'chargeType'
     * ];
     *
     *
     *
     * @param $order
     * @return bool
     */
    public static function createOrder($order)
    {
         $post = [
             'proid'=>$order['proid'],
        	 'cardnum'=>$order['cardnum'],
             'orderid'=>$order['orderid'],
             'game_userid'=>$order['game_userid'],
             'gasCardTel'=>$order['gasCardTel'],
             'gasCardName'=>$order['gasCardName'],
             'chargeType'=>$order['chargeType'],
             'key'=>self::KEY,
             'sign'=>self::sign($order),
         ];
         $requset = self::httpCurl(self::URL_ORDER,$post);
         //开发纪录
         $array_data = json_decode($requset,true);

        if($array_data['error_code'] == 0) {
            //更新订单
            if( $array_data['result']['game_state'] == 0) {
                die('订单提交 充值中');
            }
            //更新订单
            if($array_data['result']['game_state'] == 1) {
                die('订单充值成功');
            }
            return true;
        }
        die('充值失败');
        return false;
    }


    /** 检查油卡的订单状态
     * @param $orderid
     * @return array
     */
    public static function checkOrder($orderid)
    {
        //接口查询
        $post = [
            'orderid'=>$orderid,
            'key'=>self::KEY,
        ];
        $requset = self::httpCurl(self::URL_CHECK,$post);
        $array_data = json_decode($requset,true);

        //成功
        if($array_data['error_code'] == 0 && $array_data['result']['game_state'] == 1) {
            return 1;
        }

        //失败
        if($array_data['error_code'] == 0 && $array_data['result']['game_state'] == 9) {
            return 9;
        }
        //其他状态
        if($array_data['error_code'] == 0 && $array_data['result']['game_state'] == 0) {
            return 0;
        }
        return false;
    }

    /** 生成签名
     * @param $data
     * @return string
     */
    public static function sign($data)
    {
//        是	string	校验值，md5(OpenID+key+proid+cardnum+game_userid+orderid)，OpenID在个人中心查询。加密结果转为32位小写
        return md5(self::OPEN_ID.self::KEY.$data['proid'].$data['cardnum'].$data['game_userid'].$data['orderid']);
    }


    /** 充值回调成功接口 聚合数据回调
     * @param $data
     */
    public static function juheCallBack($data)
    {
        $orderid = $data['orderid'];
        $status = $data['sta'];  //1 和 9
        $sign = $data['sign'];
        // TODO
        die('success');
    }

}