<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    const TABLE = 'schools';

    protected $table = self::TABLE;

    protected $fillable = [
        'sysname',
        'title',
        'address',
        'phone',
        'owner_id'
    ];

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function pupils()
    {
        return $this->hasMany(Pupil::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, Teacher::TABLE);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, Subject::TABLE);
    }
}
