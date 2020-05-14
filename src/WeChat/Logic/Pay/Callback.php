<?php
//+-------------------------------------------------------------------------
//| Just do it !
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-------------------------------------------------------------------------
//| 回调示例
//+-------------------------------------------------------------------------


$resxml = file_get_contents("php://input");
$jsonxml = json_encode(simplexml_load_string($resxml, 'SimpleXMLElement', LIBXML_NOCDATA));
$result = json_decode($jsonxml, true); //转成数组
if ($result) {
    $out_trade_no = $result['out_trade_no'];    // 订单号
    if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
        //业务处理

        /*if(处理成功){
            return sprintf("<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>");
        }else{
            echo 'ERROR';
        }*/
    }
}