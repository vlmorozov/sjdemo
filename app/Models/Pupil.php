<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pupil extends Model
{
    use CurrentSchool;

    const TABLE = 'pupils';

    protected $table = self::TABLE;

    protected $fillable = [
        'school_id',
        'user_id',
        'class_id',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
