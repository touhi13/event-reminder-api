<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Repositories\Auth\AuthInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponseTrait;

    private AuthInterface $repository;

    public function __construct(AuthInterface $repository)
    {
        $this->repository = $repository;
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $token = $this->repository->login($request);

        if (!$token) {
            return $this->ResponseError('Invalid Credentials', null, 'Invalid Credentials', 401);
        }

        $user = $this->repository->getUserByEmail($request->email);

        $data = [
            'token' => $token,
            'user'  => $user,
        ];

        return $this->ResponseSuccess($data, 'Login Successful', "Login Successful", 200);
    }

    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $user = $this->repository->register($request);

        if (!$user) {
            return $this->ResponseError('Registration Failed', 'Registration Failed', 400);
        }

        return $this->ResponseSuccess($user, 'Registration Successful', 200);
    }

}