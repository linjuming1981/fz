<?php 

namespace app\index\model;
use think\Model;

class User extends Model{
	protected $table = 'fz_user';

	public function login($name,$pass){
		$user = $this->where(['name'=>$name,'pass'=>md5($pass)])->find();
		if($user) return $user;
		return false;
	}

}