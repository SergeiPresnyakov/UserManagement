<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DBService
{
    public function getUserAvatar($id)
    {
        return DB::table('users')
            ->select('avatar')
            ->where('id', $id)
            ->first()
            ->avatar;
    }

    public function getUserStatus($id)
    {
        return DB::table('users')
            ->select('status')
            ->where('id', $id)
            ->first()
            ->status;
    }

    public function getForEdit($id)
    {
        return DB::table('users')
            ->join('users_info', 'users.id', '=', 'users_info.user_id')
            ->select(['users.id', 'name', 'job', 'phone', 'address'])
            ->where('users.id', $id)
            ->first();
    }

    public function getUserEmail($id)
    {
        return DB::table('users')
            ->select('email')
            ->where('id', $id)
            ->first()
            ->email;
    }
}