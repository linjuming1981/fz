<?php 
class DownLoader{


	public function downToFile($url, $save_path, $force=false){
		if(!$force){
			if(is_file($save_path)) return;
		}
		$code = $this->_downCode($url);
		$save_dir = dirname($save_path);
		@mkdir($save_dir,0777,true);
		$resource = fopen($save_path, 'a');
		fwrite($resource, $code);
		fclose($resource);
	}


	public function downToDir($url, $save_dir){
		$code = $this->_downCode($url);
		$filename = pathinfo($url, PATHINFO_BASENAME);
		@mkdir($save_dir,0777,true);
		$resource = fopen($save_dir.'/'.$filename, 'a');
		fwrite($resource, $code);
		fclose($resource);
	}

	private function _downCode($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		$file = curl_exec($ch);
		curl_close($ch);
		return $file;
	}



}