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
}
?>