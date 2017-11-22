<?php 
namespace app\index\controller;
use think\Controller;
// use think\model\User;
use think\Session;

class User extends Controller{


	// 登陆页
	public function login(){
		return $this->fetch();
	}


	// 提交登陆
	public function doLogin(){

		$uname = input('post.uname');
		$password = input('post.passworld');
		$userM = new \app\index\model\User;
		$user = $userM->login($uname, $password);
		if($user){
			Session::set('uid', $user['uid']);
			Session::set('uname', $user['uname']);
			$this->success("登录成功",'index/index/index');
		}else{
			$this->error("账号密码错误", 'index/user/login');
		}

	}


	// 注册页
	public function register(){
		return $this->fetch();
	}

	// 提交注册
	public function doRegister(){
		$uname = input('post.uname');
		$password = input('post.password');


		$userM = new \app\index\model\User;
		if($userM->hasUser($uname)){
			$this->error('用户已存在，请使用其他用户名');
		}

		$userM->uname = $uname;
		$userM->password = md5($password);
		$userM->save();

		$uid = $userM->uid;
		if($uid){
			Session::set('uid', $uid);
			Session::set('uname', $uname);
			$this->success('注册成功','index/index/index');
		}else{
			$this->error('新增用户失败','index/user/register');
		}

	}

}