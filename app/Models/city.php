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

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function getStateWithCity($stateId, $cityId)
    {
        try {
            $city = city::where('city_id', $cityId)->first();
            $state = null;
            if ($city) {
                $state = state::where('state_id', $stateId)->first();
            }
            return ['state' => $state->state_name, 'city' => $city->city_name];
        } catch (\Throwable $th) {
            return false;
        }
    }
}
