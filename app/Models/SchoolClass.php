<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SchoolClass extends Model
{
    use CurrentSchool;

    const TABLE = 'classes';

    protected $table = self::TABLE;

    protected $fillable = [
        'school_id',
        'letter',
        'number',
        'year',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function pupils()
    {
        return $this->hasMany(Pupil::class, 'class_id', 'id');
    }

    public function getTitleAttribute()
    {
        return $this->number . '-' . $this->letter;
    }

}
