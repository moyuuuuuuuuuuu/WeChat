<?php
//+-------------------------------------------------------------------------
//| Just do it !
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-------------------------------------------------------------------------
//| 微信支付及其他相关方法
//+-------------------------------------------------------------------------

namespace WeChat\Tool;


class Tool
{
    /**
     * 随机字符串
     * @param string $length
     * @return string
     */
    public static function getNonceStr($length = '32')
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $noncestr = '';
        while (strlen($noncestr) < $length) {
            $noncestr .= $str{rand(0, strlen($str) - 1)};
        }
        return $noncestr;
    }

    /**
     * 微信支付加密
     * @param $data
     * @return string
     */
    public function getSign($params,$api_key)
    {
        ksort($params);
        $sign = '';
        foreach ($params as $key => $value) {
            $sign .= $key . "=" . $value . "&";
        }

        $sign .= "key=" . $api_key;

        return strtoupper(md5($sign));
    }
}