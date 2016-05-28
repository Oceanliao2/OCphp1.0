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
