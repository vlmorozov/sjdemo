<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSchool extends Model
{
    const TABLE = 'user_schools';

    protected $table = self::TABLE;

    protected $fillable = [
        'user_id',
        'school_id',
    ];

}
