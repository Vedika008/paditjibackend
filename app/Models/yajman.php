<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class yajman extends Model
{
    protected $table = "yajman";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'yajman_name',
        'mobile_number',
        'state',
        'city',
        'address',
        'date_of_birth',
        'created_by',
        'created_at',
        'updated_at'
    ];

    public function cheackYajmanExist($mobile_number)
    {
        $yajmanExistOrnot = yajman::find($mobile_number);

        if ($yajmanExistOrnot) {
            return true;
        }
        return false;
    }

    public function getYajmanDetails($yajmanId)
    {
        $yajmanDetails = yajman::where('id', $yajmanId)->get()->all();

        if (count($yajmanDetails) > 0) {
            return $yajmanDetails;
        } else {
            return false;
        }


    }

}
