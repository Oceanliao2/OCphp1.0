<?php

// +----------------------------------------------------------------------
// | OCphp
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: oceanliao <oceanliaono.1@gmail.com> <qq:1576701411>
// +----------------------------------------------------------------------



final class OCphp{
	public static function run(){
        //1、设置框架常量
        self::_set_const();
        //2、创建文件目录
        self::_create_dir();
        //3、引入文件
        self::_inport_file();
        //4、运行框架应用类
        Application::run();
	}


	private static function _set_const()//设置框架常量
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

		define('ROOT_PATH',dirname(OCPHP_PATH));//项目根目录

		define('APP_PATH',ROOT_PATH. '/' . APP_NAME);//项目前台或后台目录
		define('APP_CONFIG_PATH',APP_PATH. '/Config');
		define('APP_CONTROLLER_PATH',APP_PATH. '/Controller');
		define('APP_TPL_PATH',APP_PATH. '/Tpl');
		define('APP_PUBLIC_PATH',APP_TPL_PATH. '/Public');
	}

	private static function _create_dir()//创建文件目录
	{
		$arr = array(
			APP_PATH,
			APP_CONFIG_PATH,
			APP_CONTROLLER_PATH,
			APP_TPL_PATH,
			APP_PUBLIC_PATH
		);

		foreach ($arr as $v) {
			is_dir($v) || mkdir($v,0777,true);
		}
	}
	private static function _inport_file()//载入框架所需文件
	{
		$fileArr = array(
			FUNCTION_PATH . '/function.php',
			CORE_PATH . '/Application.class.php',
			CORE_PATH . '/Controller.class.php'
		);

		foreach ($fileArr as $v) {
			require_once $v;
		}
	}

}

OCphp::run();

?>