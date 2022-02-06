<?php

namespace App\Http\Controllers\Api;

use App\Services\StoragePathWork;
use App\Http\Controllers\ApiController;

class ProfileController extends ApiController
{
    public function getAvatar($avatar)
    {
        $myServiceSPW = new StoragePathWork("users");
        return $myServiceSPW->showFile($avatar, '/users');
    }
}
