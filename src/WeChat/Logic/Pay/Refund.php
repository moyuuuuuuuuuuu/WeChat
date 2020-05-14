<?php
//+-------------------------------------------------------------------------
//| Just do it !
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-------------------------------------------------------------------------
//| 
//+-------------------------------------------------------------------------

namespace WeChat\Logic\Pay;


use WeChat\Exception\PayException;
use WeChat\Logic\Base;
use WeChat\Tool\Http;
use WeChat\Tool\Xml;

class Refund extends Base
{
    /**
     * 申请退款
     */
    const UNIFIEDORDER = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

    /**
     * 退款查询接口
     */
    const REFUNDQUERY = 'https://api.mch.weixin.qq.com/pay/refundquery';

    /**
     * 退款接口
     * @param $data
     *             参数示例
     *              out_trade_no    微信订单号
     *              total_free       标价金额
     *              refund_fee      退款金额
     *              out_refund_no   商户退款单号
     * 其他参数见微信开发文档h ttps://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_4
     * @return mixed
     */
    public function refund($param)
    {

        $data = [
            'appid' => $this->config->get('appid'),
            'mch_id' => $this->config->get('mch_id'),
            'nonce_str' => $this->tool->getNonceStr(),
            'notify_url' => $this->config->get('pay_notify'),
        ];
        $data = array_merge($data,$param);

        $data['sign'] = $this->tool->getSign($data,$this->config->get('api_key'));
        $data = Xml::array2xml($data);
        $result = Http::sendRequest(self::UNIFIEDORDER, $data,'POST',[
            CURLOPT_SSLCERT => $this->config->get('sslcert'),
            CURLOPT_SSLKEY => $this->config->get('sslkey'),
        ]);

        if($result['code'] == 400){
            //未上传证书
            throw new PayException('请先安装证书');
        }elseif(array_key_exists('errno',$result)){
            //证书错误
            throw  new PayException('curl错误，错误码'.$result['errno']);
        }else{
            $result = $result['msg'];
        }

        $result = xml::xml2array($result);

        if ($result->return_code == 'SUCCESS') {
            if ($result->result_code == 'SUCCESS') {
                return $result;
            } else {
                throw new PayException('', $result->err_code_des, __FILE__, __LINE__);
            }
        } else {
            throw new PayException($result->return_msg, $result->return_code, __FILE__, __LINE__);
        }
    }

    /**
     * 查询退款
     * @param $param
     *          参数示例
     *              transaction_id
     *              out_trade_no
     *              out_refund_no
     *              refund_id
     *              四选一
     * @return mixed
     * @throws PayException
     */
    public function refundquery($param){
        $data = [
            'appid' => $this->config->get('appid'),
            'mch_id' => $this->config->get('mch_id'),
            'nonce_str' => $this->tool->getNonceStr(),
            'notify_url' => $this->config->get('pay_notify'),
        ];
        $data = array_merge($data,$param);

        $data['sign'] = $this->tool->getSign($data,$this->config->get('api_key'));
        $data = Xml::array2xml($data);

        $result = Http::sendRequest(self::REFUNDQUERY, $data,'POST',[
            CURLOPT_SSLCERT => $this->config->get('sslcert'),
            CURLOPT_SSLKEY => $this->config->get('sslkey'),
        ]);

        if($result['code'] == 400){
            //未上传证书
            throw new PayException('请先安装证书');
        }elseif(array_key_exists('errno',$result)){
            //证书错误
            throw  new PayException('curl错误，错误码'.$result['errno']);
        }else{
            $result = $result['msg'];
        }

        if ($result->return_code == 'SUCCESS') {
            if ($result->result_code == 'SUCCESS') {
                return $result;
            } else {
                throw new PayException('', $result->err_code_des, __FILE__, _LINE__);
            }
        } else {
            throw new PayException($result->return_msg, $result->return_code, __FILE__, _LINE__);
        }
    }
}