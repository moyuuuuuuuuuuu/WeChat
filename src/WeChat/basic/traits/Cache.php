<?php
//+-----------------------------------------------------------
//| 人生是荒芜的旅行，冷暖自知，苦乐在心
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 
//+-----------------------------------------------------------

namespace wechat\basic\traits;


use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use wechat\exception\ClassNotFoundException;

/**
 * Trait Cache
 * @package wechat\basic\traits
 * @property FilesystemAdapter $cache
 */
trait Cache
{
	protected $cache;

	public function __construct(){
		if(!class_exists(FilesystemAdapter::class)){
			throw new ClassNotFoundException('缺少缓存组件FilesystemAdapter');
		}
		$this->cache = new FilesystemAdapter();
	}

	public function get(){

	}

	public function set($name,$data,$lifetime=0){

	}

	public function delete(){

	}


}