<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master_friend_request extends Model
{
    protected $table = 'master_friend_request';
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
