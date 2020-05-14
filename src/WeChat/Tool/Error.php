<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 异常接管类
//+-----------------------------------------------------------
namespace WeChat\Tool;
class Error
{
    public static function register(){
        self::checkErrorLogPath();
        // error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
        error_reporting(E_ERROR);
         // set_exception_handler — 设置用户自定义的异常处理函数
        set_exception_handler([__CLASS__,'exception']);

        // set_error_handler — 设置用户自定义的错误处理函数
        set_error_handler([__CLASS__,'handle']);

        // register_shutdown_function — 注册一个会在php中止时执行的函数
        register_shutdown_function( [ __CLASS__,'LastError' ]);
    }

    public static function LastError(){

        $last = error_get_last();
        if($last['type']==1 || $last['type']==4 ||$last['type']==16 ||$last['type']==64 ||$last['type']==128){
            self::errlog( $last['type'],$last['message'],$last['file'],$last['line'] );
        }

    }


    // 异常接管
    public static function exception($ex)
    {
        // 获取错误异常信息
        $message = $ex->getMessage();
        // 获取错误异常代码
        $code    = $ex->getCode();
        // 获取错误异常文件
        $file    = $ex->getFile();
        // 获取错误异常文件行数
        $line    = $ex->getLine();
    }

    // 错误接管
    public static function handle( $code, $message,$file ,$line )
    {
        // 记录日志
        self::errlog( $code, $message,$file ,$line );
    }


    // 错误信息收集并记录 (参数传输的顺序不一样，参数还不一样)
    public static function errlog( $code, $message,$file ,$line )
    {
        // 拼接错误信息
        $errstr  =  date('Y-m-d h:i:s')."\r\n";
        $errstr .= '  错误级别：'.$code."\r\n";
        $errstr .= '  错误信息：'.$message."\r\n";
        $errstr .= '  错误文件：'.$file."\r\n";
        $errstr .= '  错误行数：'.$line."\r\n";
        $errstr .= "\r\n";
        // error_log — 发送错误信息到某个地方
        @error_log($errstr,3, Config::instance()->get('sys_error_log_path'));
    }

    /**
     * 检查日志文件不存在则创建
     * @return mixed
     * @throws \WeChat\Exception\ConfigException
     */
    public static function checkErrorLogPath(){
        $err_path = Config::instance()->get('sys_error_log_path');
        if(!is_file($err_path)){
            File::touchFile($err_path);
            return true;
        }
        return true;
    }
}