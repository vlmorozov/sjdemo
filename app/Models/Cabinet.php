<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    use CurrentSchool;

    const TABLE = 'cabinets';

    protected $table = self::TABLE;

    protected $fillable = ['school_id','title','number'];


    public function school()
    {
        return $this->belongsTo(School::class);
    }

}
