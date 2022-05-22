<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return view('index');
        } else {
            return view('login');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('account', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return $this->success('登录成功');
        }
        return $this->failure('帐号密码不匹配');
    }

    public function logout(Request $request)
    {
        Auth::guard('memberCard')->logout();
        Auth::guard('memberPassword')->logout();
        Auth::guard('admin')->logout();
        return $this->success('登出成功');
    }
}
