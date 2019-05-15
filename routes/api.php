<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
	'namespace' => 'App\Http\Controllers\Api'
], function($api){
	$api->post('verificationCodes', 'VerificationCodesController@store')
		->name('api.verificationCodes.store');
});