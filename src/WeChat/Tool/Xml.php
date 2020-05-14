<?php
//+-------------------------------------------------------------------------
//| Man barricades against himself.
//+-------------------------------------------------------------------------
//| Author:Janas <janmas@126.com> Time:2020/05/01 11:11
//+-------------------------------------------------------------------------
//| 
//+-------------------------------------------------------------------------


namespace WeChat\Tool;


class Xml
{
    public static function array2xml($data){
        $xml = '<xml>';
        foreach($data as $key=>$value){
            $xml .= "<{$key}>{$value}</{$key}>";
        }
        return $xml.'</xml>';
    }

    public static function xml2array($xml,$getObj = true){
        $obj = simplexml_load_string($xml,"SimpleXMLElement", LIBXML_NOCDATA);
        if($getObj){
            $array = json_decode(json_encode($obj));
        }else{
            $array = json_decode(json_encode($obj),true);
        }
        return $array;
    }
}