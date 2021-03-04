<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'users_info';

    protected $fillable = [
        'user_id',
        'job',
        'phone',
        'address',
        'vk',
        'telegram',
        'instagram'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
