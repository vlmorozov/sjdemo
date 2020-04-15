<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use CurrentSchool;

    const TABLE = 'teachers';

    protected $table = self::TABLE;

    protected $fillable = ['user_id', 'school_id'];


    public function school()
    {
        return $this->belongsTo(School::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, TeacherSubjects::TABLE);
    }
}
