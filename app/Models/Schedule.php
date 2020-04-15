<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use CurrentSchool;

    const TABLE = 'schedules';

    protected $table = self::TABLE;

    protected $fillable = [
        'dateFrom',
        'dateTo',
        'classes_id'
    ];

    protected $dates = [
        'dateFrom',
        'dateTo',
    ];

    //
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function scheduleSubjects()
    {
        return $this->hasMany(ScheduleSubject::class);
    }

    public function scheduleWeek()
    {
        return $this->hasMany(ScheduleWeek::class);
    }

    public function scheduleLessons()
    {
        return $this->hasMany(ScheduleLesson::class);
    }
}
