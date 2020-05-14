<?php
//+-------------------------------------------------------------------------
//| Just do it !
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-------------------------------------------------------------------------
//| 查询订单
//+-------------------------------------------------------------------------

namespace WeChat\Logic\Pay;


use WeChat\Exception\PayException;
use WeChat\Logic\Base;
use WeChat\Tool\Http;
use WeChat\Tool\Xml;

class Query extends Base
{
    const ORDERQUERY = 'https://api.mch.weixin.qq.com/pay/orderquery'; //订单查询接口

    /**
     * 查询订单
     * @param array $data
     *          参数示例
     *              transaction_id 微信订单
     *              out_trade_no 商家订单
     *              两者二选一
     * @return mixed
     * @throws PayException
     */
    public function query($data=[]){
        $system = [
            'appid' => $this->config->get('appid'),
            'mch_id' => $this->config->get('mch_id'),
            'noncestr' => $this->tool->getNonceStr(),
        ];

        $data  =array_merge($data,$system);
        $data['sign'] = $this->tool->getSign($data,$this->config->get('api_key'));

        $data = Xml::array2xml($data);

        $res = Xml::xml2array(Http::post(self::ORDERQUERY,$data));

        if($res['return_code'] == 'SUCCESS'){
            if($res['result_code'] == 'SUCCESS'){
                return $res;
            }else{
                throw new PayException('',$res['err_code']);
            }
        }else{
            throw new PayException($res['return_msg']);
        }
        return $res;
    }
}