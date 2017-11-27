<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller{

	public function _initialize(){
		$gv = 1;
		$this->assign('gv', $gv);

	}

	public function index(){
		return $this->fetch();
	}


	// http://localhost/fz/public/index/index/downPage
	public function downPage(){
		if()
		return $this->fetch();
	}


	public function doDownPage(){
		$url = input('url');
		$html_path = input('html_path');
		$js_dir = input('js_dir');
		$css_dir = input('css_dir');
		$css_image_dir = input('css_image_dir');

		$ht = new \HtmlTool();
		$ht->setUrl($url);
		$ht->setJsDir($js_dir);
		$ht->setCssDir($css_dir);
		$ht->setCssImageDir($css_image_dir);
		$ht->setHtmlPath($html_path);

		$ht->downPage();

	}



}
