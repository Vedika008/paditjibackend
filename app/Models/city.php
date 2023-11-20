<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class city extends Model
{
    protected $table = "city";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id',
        'city_name',
        'state_id'
    ];

    public function getcityListById($stateId)
    {
        $cityList = city::where('state_id', $stateId)->get()->all();
        if (count($cityList) > 0) {
            return $cityList;
        }
        return false;
    }
}
