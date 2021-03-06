<?php

$api = app('Dingo\Api\Routing\Router');

// v1 版本
$api->version('v1', [
	// 命名空间
	'namespace' => 'App\Http\Controllers\Api'
], function($api){
	$api->group([
		// 固定验证
		'middleware' => 'api.throttle',
		// 次数
		'limit' => config('api.rate_limits.sign.limit'),
		// 时常 单位分钟
		'expires' => config('api.rate_limits.sign.expires'),
	], function($api){
		// 短信验证码
		$api->post('verificationCodes', 'VerificationCodesController@store')
			->name('api.verificationCodes.store');
		// 用户注册
		$api->post('users', 'UsersController@store')
			->name('api.users.store');
		// 图片验证码
		$api->post('captchas', 'CaptchasController@store')
		    ->name('api.captchas.store');
		// 第三方登录
		$api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
		    ->name('api.socials.authorizations.store');
		// 登录
		$api->post('authorizations', 'AuthorizationsController@store')
		    ->name('api.authorizations.store');
		// 刷新token
		$api->put('authorizations/current', 'AuthorizationsController@update')
		    ->name('api.authorizations.update');
		// 删除token
		$api->delete('authorizations/current', 'AuthorizationsController@destroy')
		    ->name('api.authorizations.destroy');
	});
});