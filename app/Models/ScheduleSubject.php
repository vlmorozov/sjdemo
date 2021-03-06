<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSubject extends Model
{
    use CurrentSchool;

    const TABLE = 'schedule_subjects';

    protected $table = self::TABLE;

    protected $fillable = [
        'schedule_id',
        'school_id',
        'subject_id',
        'teacher_id',
        'cabinet_id',
    ];

    //
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }
}
