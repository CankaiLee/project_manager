<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    protected $loginAfterSignUp = true;

    /**
     * @param RegisterAuthRequest $request
     * @return JsonResponse
     */
    public function register(RegisterAuthRequest $request)
    {
        $user = new User();
        $user->name = '';
        $user->email = $request->get('email');
        $user->mobile = $request->get('mobile');
        $user->intro = '';
        $user->password = bcrypt($request->get('password'));
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return $this->_output_success('注册成功', $user);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');

        $jwt_token = null;

        if (! $jwt_token = JWTAuth::attempt($input)) {
            return $this->_output_error('Invalid Email or Password');
        }

        return $this->_output_success('登录成功', ['token' => $jwt_token]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $this->validate($request, [
                'token' => 'required'
            ]);
        } catch (ValidationException $e) {
            $this->_output_exception($e);
        }

        try {
            JWTAuth::invalidate($request->get('token'));

            return $this->_output_success('登出成功');
        } catch (JWTException $exception) {
            return $this->_output_error('登出失败');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request)
    {
        try {
            $this->validate($request, [
                'token' => 'required'
            ]);
        } catch (ValidationException $e) {
            $this->_output_exception($e);
        }

        $user = JWTAuth::authenticate($request->get('token'));

        return $this->_output_success('个人资料', ['user' => $user]);
    }
}
