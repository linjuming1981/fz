<?php 
namespace app\index\controller;
use think\Controller;
use think\model\User;

class User extends controller{

	public function login(){
		return $this->fetch();
	}

	public function doLogin(){
		$username = input('post.uname');
		$password = input('post.passworld');
		$userM = new User;
        $user = $userM->login($username, $password);
		if($user){
			Session::set('uid', $user['uid']);
            Session::set('uname', $user['uname'])
            $this->success("登录成功"，'index/index/index');
		}else{
            $this->error("账号密码错误"， 'index/user/login');
        }
	}

}