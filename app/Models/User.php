<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmailContract, JWTSubject {
	
	use MustVerifyEmailTrait;
	protected $fillable = [
		'name',
		'email',
		'password',
		'avatar',
		'phone',
		'introduction',
		'weixin_openid',
		'weixin_unionid'
	];
	protected $hidden = [
		'password',
		'remember_token',
	];
	protected $casts = [
		'email_verified_at' => 'datetime',
	];
	use Notifiable {
		notify as protected laravelNotify;
	}
	
	public function notify($instance) {
		// 如果要通知的人是当前用户，就不必通知了！
		if($this->id == Auth::id()) {
			return;
		}
		// 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
		if(method_exists($instance, 'toDatabase')) {
			$this->increment('notification_count');
		}
		$this->laravelNotify($instance);
	}
	
	public function topics() {
		return $this->hasMany(Topic::class);
	}
	
	public function replies() {
		return $this->hasMany(Reply::class);
	}
	
	public function isAuthorOf($model) {
		return $this->id == $model->user_id;
	}
	
	public function markAsRead() {
		$this->notification_count = 0;
		$this->save();
		$this->unreadNotifications->markAsRead();
	}
	
	public function getJWTIdentifier() {
		return $this->getKey();
	}

	public function getJWTCustomClaims() {
		return [];
	}
}
