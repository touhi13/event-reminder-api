<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepo implements AuthInterface
{
    public function login($data): ?string
    {
        $credentials = $data->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return null;
        }

        return $token;
    }

    public function register($data): ?User
    {
        try {
            return User::create([
                'name'     => $data->name,
                'email'    => $data->email,
                'password' => $data->password,
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getUser(int $id): ?User
    {
        return User::find($id);
    }
}
