<?php

// +----------------------------------------------------------------------
// | OCphp
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: oceanliao <oceanliaono.1@gmail.com> <qq:1576701411>
// +----------------------------------------------------------------------



final class OCphp{
	public static function run()
	{
        //1、设置框架常量
        self::_set_const();
        defined('DEBUG') || define('DEBUG',false);
        if(DEBUG)//如果开启调试模式
        {
        	//2、创建文件目录
	        self::_create_dir();
	        //3、引入文件
	        self::_inport_file();
        }
        else
        {
        	//屏蔽所有错误
        	error_reporting(0);
        	require TEMP_PATH . '/~boot.php';
        }
        //4、运行框架应用类
        Application::run();
	}


	/**
     * 设置框架常量
     */
	private static function _set_const()
	{
		//__FILE__ 获得当前路径
		//var_dump(__FILE__);
		$path = str_replace('\\', '/', __FILE__);
		define('OCPHP_PATH',dirname($path));
		define('CONFIG_PATH',OCPHP_PATH.'/Config');
		define('DATA_PATH',OCPHP_PATH.'/Data');
		define('LIB_PATH',OCPHP_PATH.'/Lib');
		define('CORE_PATH',LIB_PATH.'/Core');
		define('FUNCTION_PATH',LIB_PATH.'/Function');
		//项目根目录
		define('ROOT_PATH',dirname(OCPHP_PATH));
		//临时目录
		define('TEMP_PATH',ROOT_PATH . '/Temp');
		//日志目录
		define('LOG_PATH',TEMP_PATH . '/Log');
		//应用目录
		define('APP_PATH',ROOT_PATH. '/' . APP_NAME);//项目前台或后台目录
		define('APP_CONFIG_PATH',APP_PATH. '/Config');
		define('APP_CONTROLLER_PATH',APP_PATH. '/Controller');
		define('APP_TPL_PATH',APP_PATH. '/Tpl');
		define('APP_PUBLIC_PATH',APP_TPL_PATH. '/Public');
		define('OCPHP_VERSON','1.0');
		define('IS_POST',$_SERVER['REQUEST_METHOD'] == 'POST' ? true : false);
	  if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
			define('IS_AJAX',true);
		}
		else
		{
			define('IS_AJAX',false);
		}
		//创建公共常量
		define('COMMON_PATH',ROOT_PATH . '/Common');
		//公共配置项文件夹
		define('COMMON_CONFIG_PATH',COMMON_PATH . '/Config');
		//公共模型
		define('COMMON_MODEL_PATH',COMMON_PATH . '/Model');
		//公共库
		define('COMMON_LIB_PATH',COMMON_PATH . '/Lib');
	}

	/**
     * 创建文件目录
     */
	private static function _create_dir()
	{
		$arr = array(
			COMMON_CONFIG_PATH,
			COMMON_MODEL_PATH,
			COMMON_LIB_PATH,

			APP_PATH,
			APP_CONFIG_PATH,
			APP_CONTROLLER_PATH,
			APP_TPL_PATH,
			APP_PUBLIC_PATH,
			TEMP_PATH,
			LOG_PATH
		);

		foreach ($arr as $v) {
			is_dir($v) || mkdir($v,0777,true);
		}
	}


	/**
     * 载入框架所需文件
     */
	private static function _inport_file()
	{
		$fileArr = array(
			CORE_PATH . '/Log.class.php',
			FUNCTION_PATH . '/function.php',
			CORE_PATH . '/Application.class.php',
			CORE_PATH . '/Controller.class.php'
		);

		$str = '';

		foreach ($fileArr as $v) {
			$str .=trim(substr(file_get_contents($v), 5,-2));
			require_once $v;
		}

		$str = "<?php\r\n" .$str;
		file_put_contents(TEMP_PATH . '/~boot.php', $str) || die('access not allow');

		//复制跳转页面
		is_file(APP_TPL_PATH . '/success.html') || copy(DATA_PATH . '/Tpl/success.html', APP_TPL_PATH . '/success.html');
		is_file(APP_TPL_PATH . '/error.html') || copy(DATA_PATH . '/Tpl/error.html', APP_TPL_PATH . '/error.html');
	}

}

OCphp::run();

?>
