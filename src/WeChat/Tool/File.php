<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------


namespace WeChat\Tool;


class File
{
    /**
     * 检查文件是否存在不存在则创建
     * @param string $file
     * @return bool
     */
    public static function touchFile(string $file):bool
    {

        if(strpos($file,DIRECTORY_SEPARATOR) === false){
            if(touch($file)){
                return true;
            }else{
                return false;
            }
        }

        if(!is_file($file)){
            $dir = explode(DIRECTORY_SEPARATOR,$file);
            array_pop($dir);
            $dir = join(DIRECTORY_SEPARATOR,$dir).DIRECTORY_SEPARATOR;
            self::mkdirs($dir);
            if(@touch($file)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    /**
     * 递归创建文件夹
     * @param $path
     * @param string $mode
     * @return bool
     */
    public static function mkdirs($path,$mode=0777){
        if(!empty($path)){
            if((is_dir($path) || @mkdir($path,$mode))){
                return true;
            }else{
                $path = explode(DIRECTORY_SEPARATOR,$path);
                array_pop($path);
                $path = implode(DIRECTORY_SEPARATOR,$path);
                self::mkdirs($path);
                if(@mkdir($path,$mode)){
                    return true;
                }
            }
        }
    }
}
