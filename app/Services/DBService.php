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
            ->first();
    }
}