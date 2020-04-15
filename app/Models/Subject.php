<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use CurrentSchool;

    const TABLE = 'subjects';

    protected $table = self::TABLE;

    protected $fillable = [
        'title',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, SchoolClassSubject::TABLE);
    }
}
