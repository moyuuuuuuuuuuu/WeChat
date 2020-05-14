<?php
//+-------------------------------------------------------------------------
//| Man barricades against himself.
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com> Time:2020/05/01 11:02
//+-------------------------------------------------------------------------
//| 支付相关
//+-------------------------------------------------------------------------


namespace WeChat\Logic\Pay;

use WeChat\Exception\PayException;
use WeChat\Logic\Base;
use WeChat\Tool\Http;
use WeChat\Tool\Xml;

#TODO:适配多种支付场景需重新修改
class Pay extends Base
{

    const UNIFIEDORDER = 'https://api.mch.weixin.qq.com/pay/unifiedorder';//统一下单接口
    const CLOSEORDER = 'https://api.mch.weixin.qq.com/pay/closeorder'; //关闭订单接口
    /**
     * 统一下单
     * @param $data 需要提交的参数
     *          参数示例
     *              body  商品描述
     *              out_trade_no 订单号
     *              total_fee 金额
     *              spbill_create_ip 用户ip
     *              trade_type 交易类型
     *            其他参数请参看微信支付开发文档 https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_1
     * @return Object
     */
    public function unifiedorder($data)
    {
        //检测参数是否正常

        if (array_key_exists('spbill_create_ip', $data)) {
            $data['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        }

        if($data['trade_type'] == 'NATIVE' && !array_key_exists('product_id',$data)){
            throw new PayException('参数异常，缺少product_id');
        }



        $params = [
            'appid' => $this->config->get('appid'),
            'mch_id' => $this->config->get('mch_id'),
            'nonce_str' => $this->tool->getNonceStr(),
            'notify_url' => $this->config->get('pay_notify'),
        ];

        $params = array_merge($params,$data);
        unset($data);
        $params['sign'] = $this->tool->getSign($params,$this->config->get('api_key'));
        $params = Xml::array2xml($params);

        $result = xml::xml2array(Http::post(self::UNIFIEDORDER, $params));
        if ($result->return_code == 'SUCCESS') {
            if ($result->result_code == 'SUCCESS') {
                $return_data = [];
                switch($result->trade_type){
                    case 'MWEB':
                        $return_data = [
                            'appId' => $result->appid,
                            'timeStamp' => time(),
                            'package' => 'prepay_id=' . $result->prepay_id,
                            'signType' => 'MD5',
                            'nonceStr' => $this->tool->getNonceStr(),
                        ];
                        $return_data['paySign'] = $this->tool->getSign($return_data,$this->config->get('api_key'));
                        break;
                    case 'JSAPI':
                        $return_data = [
                            'appId' => $result->appid,
                            'timeStamp' => time(),
                            'package' => 'prepay_id=' . $result->prepay_id,
                            'signType' => 'MD5',
                            'nonceStr' => $this->tool->getNonceStr(),
                        ];
                        $return_data['paySign'] = $this->tool->getSign($return_data,$this->config->get('api_key'));
                        break;
                    case 'APP':
                        $return_data = [
                            'appid' => $result->appid,
                            'prepayid' => $result->prepay_id,
                            'package' => 'Sign=WXPay',
                            'noncestr' => $this->tool->getNonceStr(),
                            'timestamp' => time(),
                            'partnerid' => $this->config->get('partnerid'),
                        ];
                        $return_data['sign'] = $this->tool->getSign($return_data,$this->config->get('api_key'));
                        break;

                    case 'NATIVE':
                        $return_data = [
                            'appid' => $result->appid,
                            'product_id' => $params['product_id'],
                            'mch_id' => $result->mch_id,
                            'time_stamp' => time(),
                            'nonce_str' => $this->tool->getNonceStr()
                        ];
                        $return_data['sign'] = $this->tool->getSign($return_data,$this->config->get('api_key'));
                        break;

                    default:
                        $return_data = json_decode($result,true);
                        break;
                }
                return $return_data;
            } else {
                throw new PayException('', $result->err_code_des, __FILE__, __LINE__);
            }
        } else {
            throw new PayException($result->return_msg, $result->return_code, __FILE__, __LINE__);
        }
    }

    /**
     * 关闭订单
     * @param $order_sn
     * @return mixed
     * @throws PayException
     */
    public function closeorder($order_sn){
        $data = [
            'appid' => $this->config->get('appid'),
            'mch_id' => $this->config->get('mch_id'),
            'nonce_str' => $this->tool->getNonceStr(),
            'notify_url' => $this->config->get('pay_notify'),
            'out_trade_no' => $order_sn
        ];

        $data['sign'] = $this->tool->getSign($data,$this->config->get('api_key'));
        $data = Xml::array2xml($data);

        $result = xml::xml2array(Http::post(self::CLOSEORDER, $data));

        if($result->return_code == 'SUCCESS'){
            if($result->result_code == 'SUCCESS'){
                return $result;
            }else{
                throw new PayException($result->err_code_des,$result->err_code);
            }
        }else{
            throw new PayException($result->return_code,$result->return_msg);
        }
    }




}