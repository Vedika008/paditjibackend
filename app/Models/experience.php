<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class experience extends Model
{
    protected $table = "Experience";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'experience'
    ];

    public function getExperienceList()
    {
        $ExperienceList =  experience:: all();
          if (count($ExperienceList) > 0) {
            return $ExperienceList;
        }
        return false;
    }
}
