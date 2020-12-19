<?php
//+-----------------------------------------------------------
//| 人生是荒芜的旅行，冷暖自知，苦乐在心
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 
//+-----------------------------------------------------------

namespace wechat\exception;


use Throwable;

class HttpRequestException extends \RuntimeException
{
	public function __construct( $message = "", $code = 0, Throwable $previous = null ){ parent::__construct($message, $code, $previous); }
}