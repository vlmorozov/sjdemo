<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubjects extends Model
{
    const TABLE = 'teacher_subjects';

    protected $table = self::TABLE;

    protected $fillable = [
        'teacher_id',
        'subject_id',
    ];

}
