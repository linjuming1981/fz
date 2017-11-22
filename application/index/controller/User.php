<?php 
namespace app\index\controller;
use think\Controller;
use think\model\User;
use think\Session;

class User extends controller{

	public function login(){
		return $this->fetch();
	}

	public function doLogin(){
		$uname = input('post.uname');
		$password = input('post.passworld');
		$userM = new User;
		$user = $userM->login($uname, $password);
		if($user){
			Session::set('uid', $user['uid']);
			Session::set('uname', $user['uname']);
			$this->success('登陆成功');
		}else{
			$this->error('登陆失败');
		}

	}

}