<?php 
namespace app\index\controller;
use think\Controller;
use think\model\User;

class User extends controller{

	public function login(){
		return $this->fetch();
	}

	public function doLogin(){
		$username = input('post.username');
		$password = input('post.passworld');
		$userM = new User;
		if($userM->login($username, $password)){
			
		}
	}

}