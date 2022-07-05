<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validated)) {
            $data = [
                'token' => Auth::user()->createToken('authentication')->plainTextToken
            ];
            return $this->success($data, __('auth.logged_in'));
        }

        return $this->error(message: __('auth.failed'));
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success(message: __('auth.logged_out'));
    }
}
