<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class APITokenController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
       return $this->success([
           'token' => Auth::user()->createToken('api')->plainTextToken
       ]);
    }

}
