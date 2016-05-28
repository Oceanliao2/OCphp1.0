<?php

// +----------------------------------------------------------------------
// | OCphp
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: oceanliao <oceanliaono.1@gmail.com> <qq:1576701411>
// +----------------------------------------------------------------------


/**
 * 错误终止函数
 * @param String or ayyay()  $error 错误保存介质
 * @param String $level 错误等级
 * @param int $type 错误类型
 * @param String $dest 日志路径
 */
function halt($error,$level='ERROR',$type=3,$dest=NULL)
{
	if(is_array($error))
	{
		//写入日志
		Log::write($error['message'],$level,$type,$dest);
	}
	else
	{
		//写入日志
		Log::write($error,$level,$type,$dest);
	}

	$e = array();
	//开启调试模式的操作
	if(DEBUG)
	{
		if(!is_array($error))
		{
			$trace = debug_backtrace();//http://www.w3school.com.cn/php/func_error_debug_backtrace.asp
			$e['message'] = $error;
			$e['file'] = $trace[0]['file'];
			$e['line'] = $trace[0]['line'];
			$e['class'] = isset($trace[0]['class']) ? $trace[0]['class'] : '';
			$e['function'] = isset($trace[0]['function']) ? $trace[0]['function'] : '';
			ob_start();
			debug_print_backtrace();//http://www.w3school.com.cn/php/func_error_debug_print_backtrace.asp
			$e['trace'] = htmlspecialchars(ob_get_clean());
		}
		else
		{
			$e = $error;
		}
	}
	//关闭调试模式的操作
	else
	{
		if($url = C('ERROR_URL'))
		{
			go($url);
		}
		else
		{
			$e['message'] = C('ERROR_MSG');
		}
	}

	include DATA_PATH . '/Tpl/halt.html';
	die;
}


/**
 * 打印函数
 */

function p($arr)
{
	if(is_bool($arr))
	{
		var_dump($arr);
	}
	else if(is_null($arr))
	{
		var_dump(NULL);
	}
	else
	{
		echo '<pre style="border: 1px solid rgb(204, 204, 204); padding: 10px; margin-top: 5px; margin-bottom: 5px; line-height: 1.4; font-size: 0.8em; font-family: Menlo, Monaco, Consolas;  background-color: rgb(245, 245, 245);">' . print_r($arr,true) . '</pre>';
	}
}

/**
 * 页面跳转
 * @param String $url 地址
 * @param String $time 时间
 * @param String $msg 消息
 */
function go($url,$time=0,$msg='')
{
	if(!headers_sent())
	{
	 	$time == 0 ? header('Location:' . $url) : header("refresh:{$time};url={$url}");
	 	die($msg);
	}
	else
	{
	 	echo "<meta http-equiv='Refresh' content='{$time};URL={$url}'/>";
	 	if($time) die($msg);
  	}

}

/**
 * 加载配置项，C($sysConfig) C($userConfig)
 * 读取某个配置项 C('CODE_LEN')
 * 临时动态改变配置项 C('CODE_LEN',20);
 * 读取所有配置项 C();
 * @param String $var 配置项
 * @param String $value 配置值
 * @return array $config
 */

function C($var = NULL, $value = null)
{
	static $config = array();

	//加载配置项
	if(is_array($var))
	{
		//合并数组
		$config = array_merge($config,array_change_key_case($var,CASE_UPPER));
		return;
	}

	//读取某个配置项 临时动态改变配置项
	if(is_string($var))
	{
		$var = strtoupper($var);//把字符串转换为大写。
		//两个参数传递
		if(!is_null($value))
		{
			$config[$var] = $value;
			return;
		}
		//return isset($config[$var]);
		return $config[$var];
	}

	//返回所有配置项
	if(is_null($var) && is_null($value))
	{
		return $config;
	}
}


/**
 * 获得所有常量
 */

function print_const()
{
	$const = get_defined_constants(true);
	p($const['user']);
}
?>
