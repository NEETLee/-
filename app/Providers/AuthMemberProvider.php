<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class AuthMemberProvider extends EloquentUserProvider
{

    /**
     * 读卡提供的密码为加密密码
     * @param UserContract $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        return $user->getAuthPassword() === $credentials['password'];
    }

}
