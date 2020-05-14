<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 日志类注入到Base类
//+-----------------------------------------------------------

namespace WeChat\Tool;

class Log
{
    /**
     * 句柄
     * @var null
     */
    protected static $handle = NUll;
    /**
     * 日志文件路径
     * @var null
     */
    protected static $log_path = NUll;
    /**
     * 每个日志文件最大字节数 默认1M
     * @var float|int
     */
    protected static $log_file_max_size = 1024*1024;

    protected static $currentFileName = '';

    protected static $logStatus = true;

    public function __construct()
    {
        $config = Config::instance();
        self::$logStatus = $config->get('log_status');
        if(!self::$logStatus){
            return true;
        }
        self::$log_path = $config->get('log_path');
        $log_file_max_size = $config->get('log_file_max_size');
        if(!empty($log_file_max_size)){
            self::$log_file_max_size = $log_file_max_size;
        }
        self::$currentFileName = self::$log_path . date('y-m') . DIRECTORY_SEPARATOR . date('d') . '.log';
        $this->init();
    }

    /**
     * 检查日志文件是否可写
     * @throws \Exception
     */
    private function init(){
        if(!is_writable(dirname(dirname(self::$log_path)))){
            exit(self::$log_path.'的父级权限不足，不可写入，请先调整权限');
        }

        if(!is_file(self::$currentFileName)){
            File::touchFile(self::$currentFileName);
        }
    }
    /**
     * 日志文件写入
     * @param $msg
     * @param $code
     * @param $level
     */
    public static function write($msg='',$error='',$file='',$line=''){
        if(!self::$logStatus){
            return true;
        }
        self::$handle = fopen(self::$currentFileName,'aw');
        $str = '-----------------------------------------------------------------------------------------------------------------------------------------------------' . "\r\n";
        $str .= '[ ' . date('Y-m-d H:i:d',time()) . ' ]    ' . $_SERVER['HTTP_HOST']  . $_SERVER['SCRIPT_NAME'] . ' ' . $_SERVER['SERVER_ADDR'] . "\r\n";
        $str .= '[ 运行时间：' . (microtime(true)-(float)$_SERVER['REQUEST_TIME']) . 's ]  ';
        $str .= '[ 脚本路径 ] ' . $_SERVER['SCRIPT_FILENAME']."\r\n";
        if(!is_null($error)){
            $str .= '[ 错误码 ] '.$error . "\r\n";
        }
        $str .= '[ 提示信息 ] ' . $msg."\r\n";
        if(!empty($file)){
            $str .= '[ 错误位置 ] FILE:' . $file;
        }
        if(!empty($line)){
            $str .= ':'.$line . "\r\n";
        }
        fwrite(self::$handle,$str."\r\n");

        fclose(self::$handle);
    }



    private function closeHandle(){

    }

    /**
     * 日志文件分片（无用）
     */
    private function chunkLogFile(){
        $filename = self::$log_path . date('y-m') . DIRECTORY_SEPARATOR . date('d');
        if(file_exists($filename. '.log') || file_exists($filename. '-1.log')){
            if(@filesize($filename. '.log') >= self::$log_file_max_size || @filesize($filename. '-1.log') >= self::$log_file_max_size){
                rename($filename. '.log',$filename. '-1.log');
                $filename .= '-2.log';
                if(!is_file($filename)){
                    File::touchFile($filename);
                }
            }else{
                $filename .= '.log';
            }
        }else{
            $filename .= '.log';
            File::touchFile($filename);
        }
    }
}