<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 17.03.20
 * Time: 14:38
 */

namespace App\Models;


use Illuminate\Support\Facades\Auth;

trait CurrentSchool
{
    public static function withCurrentSchool()
    {
        return self::whereHas('school', function($query) {
            $query->where('id', Auth::user()->currentSchool()->id);
        });
    }
}
