<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class state extends Model
{
    protected $table = "state";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state_id', 'state_name'
    ];

    public function getStateList()
    {
        $stateList = state :: all();
          if (count($stateList) > 0) {
            return $stateList;
        }
        return false;
    }

    public function cities() {
        return $this->hasMany(City::class);
    }
}
