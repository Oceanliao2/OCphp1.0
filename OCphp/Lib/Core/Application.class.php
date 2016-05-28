<?php

// +----------------------------------------------------------------------
// | OCphp
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: oceanliao <oceanliaono.1@gmail.com> <qq:1576701411>
// +----------------------------------------------------------------------


final class Application{
	public static function run(){
		self::_init();//初始化框架
		self::_set_url();//设置资源路径
		spl_autoload_register(array(__CLASS__,'_autoload'));//自动载入功能
		self::_create_demo();
		self::_app_run();//实例化应用控制器
	}



	/**
     * 实例化应用控制器
     */
	private static function _app_run(){

		$a = isset($_GET[C('VAR_ACTION')]) ? $_GET[C('VAR_ACTION')] : 'index';
		$c = isset($_GET[C('VAR_CONTROLLER')]) ? $_GET[C('VAR_CONTROLLER')] : 'Index';

		define('CONTRULLER',$c);
		define('ACTION',$a);

		$c .= 'Controller';
		$obj = new $c();
		$obj->$a();
	}


    /**
     * 创建默认控制器
     */

	private static function _create_demo(){
		$path = APP_CONTROLLER_PATH . '/IndexController.class.php';
		$str = <<<str
<?php
/**
*  欢迎使用OCphp,我是作者Ocean,这款框架是一套很基础的框架原型,可作为大家的一个借鉴参考。
*  我后期会陆续完善开发文档，给想要开发属于自己的php框架的伙伴提供一个思路。
*  QQ:1576701411
*/
class IndexController extends Controller{

	public function index(){
		header("Content-Type:text/html; charset=utf-8");
		p("欢迎使用OCphp!");
	}
}
?>
str;
		is_file($path) || file_put_contents($path,$str);
	}


    /**
     * 自动载入功能
     * @param string $className 模板文件
     */
	private static function _autoload($className){

		include APP_CONTROLLER_PATH . '/' .$className. '.class.php';

	}

    /**
     * 初始化框架
     */
	private static function _init(){
		//加载配置项
		C(include CONFIG_PATH . '/config.php');

		//用户配置项
		$userPath = APP_CONFIG_PATH . '/config.php';

		$userConfig = <<<str
<?php
return array(
	//配置项 => 配置值
	);

?>
str;
		 //创建用户配置文件
		 is_file($userPath) || file_put_contents($userPath, $userConfig);
		 //加载用户配置项
		 C(include $userPath);
		 //设置默认时区
		 date_default_timezone_set(C('DEFAULT_TIME_ZONE'));
		 //是否开启session
		 C('SESSION_AUTO_START') && session_start();
	}


	/**
     * 设置资源路径
     */
	private static function _set_url(){

			$path = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
			$path = str_replace('\\', '/', $path);
			define('__APP__', $path);                           /*http://localhost/OCphp/index.php*/
			define('__ROOT__', dirname(__APP__));               /*http://localhost/OCphp*/
			define('__TPL__',__ROOT__ . '/' .APP_NAME . '/Tpl');/*http://localhost/OCphp/Home/Tpl*/
			define('__PUBLIC__',__TPL__ . '/Public');           /*http://localhost/OCphp/Home/Tpl/Public*/
	}
}
?>
