<?php 

namespace app\index\model;
use think\Model;

class User extends Model{
	protected $table = 'fz_user';

	// 登陆验证
	public function login($name,$pass){
		$user = $this->where(['name'=>$name,'pass'=>md5($pass)])->find();
		if($user) return $user;
		return false;
	}
	
	// 获取用户信息
	public function getUser($uid){
		$user = $this->where('uid',$uid)->find();
		return $user;
	}

	// 检查用户是否存在
	public function hasUser($uname){
		$user = $this->where('uname',$uname)->find();
		if($user){
			return true;
		}else{
			return false;
		}
	}
	
	

}