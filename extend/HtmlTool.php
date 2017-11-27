<?php 
class HtmlTool{

	private $url;
	private $origin_code;
	private $site_url;
	private $save_dir;
	private $css_dir;
	private $js_dir;
	private $css_image_dir;
	private $html_path;


	public function setUrl($url){
		$this->url = $url;
		$this->site_url = $this->getSiteUrl($url);
		$this->save_dir = '/www/fz_data/'.$this->getSiteHost($url);
	}


	public function setJsDir($js_dir){
		$this->js_dir = $this->_trimDir($js_dir);
	}

	public function setCssDir($css_dir){
		$this->css_dir = $this->_trimDir($css_dir);
	}

	public function setCssImageDir($css_image_dir){
		$this->css_image_dir = $this->_trimDir($css_image_dir);
	}

	public function setHtmlPath($html_path){
		$this->html_path = ltrim($html_path,'/');
	}




	public function parse(){
		$code = $this->getCodeFromUrl($this->url);
		$rs = $this->parseCode($code);
		return $rs;

	}


	public function downPage(){
		$rs = $this->parse();
		$dl = new \DownLoader();

		$html = $rs['html'];
		$html_r_dir = $this->_getHtmlRelativeDir(); // ../..


		foreach($rs['js_files'] as $k=>$v){
			$js_url = $this->getFileUrl($v);
			$js_name = basename($v);
			$local_js_path = $this->save_dir.'/'.$this->js_dir.'/'.$js_name;
			$dl->downToFile($js_url, $local_js_path);

			$html = str_replace('js_'.$k, $html_r_dir.'/'.$this->js_dir.'/'.$js_name, $html);

		}

		foreach($rs['css_files'] as $k=>$v){
			$css_url = $this->getFileUrl($v);
			$css_name = basename($v);
			$local_css_path = $this->save_dir.'/'.$this->css_dir.'/'.basename($v);
			$dl->downToFile($css_url, $local_css_path);
			$html = str_replace('css_'.$k, $html_r_dir.'/'.$this->css_dir.'/'.$css_name, $html);
			$css_url_dir = dirname($css_url);

		
			$img_dir_r = $this->_getImgRelativeDir();

			$code = file_get_contents($local_css_path);
			$code = preg_replace_callback('@url\([\'"]?(.*)[\'"]?\)@', function($match)use($css_url_dir,$img_dir_r,$dl){
				$path = $match[1];
				if(preg_match('@^/@',$path)){
					$img_url = $this->site_url.$path;
				}else if(preg_match('@^http@', $path)){
					$img_url = $path;
				}else{
					$img_url = $css_url_dir.'/'.$path;
				}
				$img_url = preg_replace('@\?\.*$@','',$img_url);
				if(!preg_match('@jpg|png|gif@',$img_url)) return $img_url;

				$img_path = $this->save_dir.'/'.$this->css_image_dir.'/'.basename($img_url);
				$dl->downToFile($img_url, $img_path);

				$rp_path = $img_dir_r.'/'.basename($img_url);
				return 'url('.$rp_path.')';

			}, $code);

			file_put_contents($local_css_path, $code);
		}


		file_put_contents($this->save_dir.'/'.$this->html_path, $html);


	}


	public function getFileUrl($v){
		if(preg_match('@^/@',$v)){
			$file_url = $this->site_url.$v;
		}else if(preg_match('@^http@',$v)){
			$file_url = $v;
		}else if(preg_match('@^\w@',$v)){
			$file_url = dirname($this->url).'/'.$v;
		}
		return $file_url;
	}


	public function getSiteUrl($url){
		$arr = parse_url($url);
		return $arr['scheme'].'://'.$arr['host'];
	}

	public function getSiteHost($url){
		$arr = parse_url($url);
		return $arr['host'];
	}





	public function getCodeFromUrl($url){
		$code = file_get_contents($url);
		return $code;
	}

	

	public function parseCode($code){
		$regex = '@<link [^>]+>|<script [^>]+>\s*</script>@isU';
		$jsCss = [];
		$code = preg_replace_callback($regex, function($match)use(&$jsCss){
			$v = $match[0];
			if(strpos($v, '.css')){
				preg_match('@href=[\'"]([^\'"]*\.css)@i', $v, $res);
				$jsCss['css'][] = $res[1];
				$key = count($jsCss['css'])-1;
				$v = str_replace($res[1], 'css_'.$key, $v);
				return $v;
			}
			if(strpos($v, '.js')){
				preg_match('@src=[\'"]([^\'"]*\.js)@i', $v, $res);
				if(!$res) return $v;
				
				$jsCss['js'][] = $res[1];
				$key = count($jsCss['js'])-1;
				$v = str_replace($res[1], 'js_'.$key, $v);
				return $v;
			}

		}, $code);
		$return = [
			'html' => $code,
			'js_files' => $jsCss['js'],
			'css_files' => $jsCss['css']
		];
		return $return;
	}

	private function _trimDir($dir){
		$dir = str_replace('\\', '/', $dir);
		$dir = preg_replace('@[\s\.]@','',$dir);
		$dir = trim($dir,'/');
		return $dir;
	}


	private function _getImgRelativeDir(){
		# public/css/index.css
		# public/image/v1/good.png
		# url(../image/v1/good.png)
		# url(../../public/image/v1/good.png)

		if($this->css_dir == ''){
			$css_dir = '.';
		}else{
			$css_dir = preg_replace('@([^/]+)@','..',$this->css_dir);
		}
		$img_dir = $css_dir.'/'.$this->css_image_dir;
		return $img_dir;
	}


	private function _getHtmlRelativeDir(){
		$html_dir = dirname($this->html_path);
		if($html_dir == '.') return $html_dir;
		$html_dir = preg_replace('@([^/]+)@','..',$html_dir);
		return $html_dir;
	}

	


}