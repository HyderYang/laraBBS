<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {
	$api->get('version', function() {
		return response('this is version v1');
	});
});

$api->version('v2', function($api) {
	$api->get('version', function() {
		return response('this is version v2');
	});
});