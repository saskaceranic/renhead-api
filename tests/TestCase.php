<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getUserByType($userType)
    {
        return User::where('type', $userType)->first();
    }

    protected function getByUserType($userType, $url)
    {
        $user = $this->getUserByType($userType);

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('api_token')->plainTextToken
        ])->get($url);
    }

    protected function postByUserType($userType, $url, $data)
    {
        $user = $this->getUserByType($userType);

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('api_token')->plainTextToken
        ])->post($url, $data);
    }

    protected function patchByUserType($userType, $url, $data)
    {
        $user = $this->getUserByType($userType);

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('api_token')->plainTextToken
        ])->patch($url, $data);
    }

    protected function deleteByUserType($userType, $url)
    {
        $user = $this->getUserByType($userType);

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('api_token')->plainTextToken
        ])->delete($url);
    }
}
