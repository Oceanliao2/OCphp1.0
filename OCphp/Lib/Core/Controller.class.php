<?php
// +----------------------------------------------------------------------
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
