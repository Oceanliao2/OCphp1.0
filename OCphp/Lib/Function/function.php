<?php

// +----------------------------------------------------------------------
// | OCphp
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: oceanliao <oceanliaono.1@gmail.com> <qq:1576701411>
// +----------------------------------------------------------------------



function p($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

//加载配置项，C($sysConfig) C($userConfig)
//读取某个配置项 C('CODE_LEN')
//临时动态改变配置项 C('CODE_LEN',20);
//读取所有配置项 C();
function C($var = NULL, $value = null){
	static $config = array();
	
	//加载配置项
	if(is_array($var)){
		//合并数组
		$config = array_merge($config,array_change_key_case($var,CASE_UPPER));
		return;
	}

	//读取某个配置项 临时动态改变配置项
	if(is_string($var)){
		$var = strtoupper($var);//把字符串转换为大写。
		//两个参数传递
		if(!is_null($value)){
			$config[$var] = $value;
			return;
		}
		//return isset($config[$var]);
		return $config[$var];
	}

	//返回所有配置项
	if(is_null($var) && is_null($value)){
		return $config;
	}
}