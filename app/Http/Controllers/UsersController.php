<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class UsersController extends Controller {
	
	public function __construct() {
		$this->middleware('auth', ['except' => ['show', 'send']]);
	}
	
	public function show(User $user) {
		return view('users.show', compact('user'));
	}
	
	public function edit(User $user) {
		$this->authorize('update', $user);
		return view('users.edit', compact('user'));
	}
	
	public function update(UserRequest $request,ImageUploadHandler $uploader, User $user) {
		$this->authorize('update', $user);
		$data = $request->all();
		
		if ($request->avatar) {
			$result = $uploader->save($request->avatar, 'avatars', $user->id, 416);
			if($result){
			    $data['avatar'] = $result['path'];
			}
		}
		
		$user->update($data);
		return redirect()->route('users.show', $user->id)->with('success', '更新成功');
	}
	
	public function send() {
		$sms = app('easysms');
		try {
			$sms->send(13722253316, [
				'content'  => '您的验证码是：1234。请不要把验证码泄露给其他人。如非本人操作，可不用理会！',
			]);
		} catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
			$message = $exception->getException('huyi')->getMessage();
			dd($message);
		}
	}
}
