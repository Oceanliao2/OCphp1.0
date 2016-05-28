<?php
// +----------------------------------------------------------------------
// | OCphp
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: oceanliao <oceanliaono.1@gmail.com> <qq:1576701411>
// +----------------------------------------------------------------------



/**
 * 日志处理类
 */
class Log{

    /**
     * 写日志功能
     * @param string $msg 信息
     * @param string $level 错误等级
     * @param string $type 类型
     * @param string $dest 日志路径
     * @return void
     */
	static public function write($msg,$level='ERROR',$type=3,$dest=NULL){
		if(!C('SAVE_LOG')) return;
		if(is_null($dest)){
			$dest = LOG_PATH . '/' . date('Y_m_d') . ".log";
		}

		if(is_dir(LOG_PATH)) error_log("[TIME]:" . date('Y_m_d H:i:s') . "{$level}:{$msg}\r\n",$type,$dest);
	}
}// +----------------------------------------------------------------------
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
?>// +----------------------------------------------------------------------
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
?>// +----------------------------------------------------------------------
// | OCphp
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: oceanliao <oceanliaono.1@gmail.com> <qq:1576701411>
// +----------------------------------------------------------------------



/**
 * 父类controller
 */

class Controller{

	private $var = array();

	/**
	 * 初始化方法
	 * __construct 魔术方法，每次实例化类是会自动执行此方法。
	 */

	public function __construct(){
		if(method_exists($this, '__init')){
			$this->__init();
		}

		if(method_exists($this, '__auto')){
			$this->__auto();
		}
	}

	/**
	 * 载入模版
	 */
	protected function display($tpl=NULL)
	{
		if(is_null($tpl))
		{
		 	$path = APP_TPL_PATH . '/' . CONTRULLER . '/' . ACTION . '.html';
		}
		else
		{
			$suffix = strrchr($tpl,'.');
			$tpl = empty($suffix) ? $tpl . '.html' : $tpl;
			$path = APP_TPL_PATH . '/' . CONTRULLER . '/' . $tpl;
		}
		if(!is_file($path)) halt($path . '模版文件不存在');
		extract($this->var);//http://www.w3school.com.cn/php/func_array_extract.asp
		include $path;
	}

	/**
	 * 模版传值
	 * @param String $var 变量
	 * @param String $value 值
	 */

	 protected function assign($var,$value)
	 {
		 $this->var[$var] = $value;
	 }



	/**
	 * 成功提示方法
	 */
	protected function success($msg ,$url=null,$time=1){
		$url = $url ? "window.location.href='" . $url . "'" : 'window.history.back(-1)';
		include APP_TPL_PATH . '/success.html';
		die;
	}

	/**
	 * 错误提示方法
	 */
	protected function error($msg ,$url=null,$time=3){
		$url = $url ? "window.location.href='" . $url . "'" : 'window.history.back(-1)';
		include APP_TPL_PATH . '/error.html';
		die;
	}
}
?>