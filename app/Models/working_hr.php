<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class working_hr extends Model
{
    protected $table = "working_hr";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'working_hr'
    ];

    public function getworkingHrList()
    {
        $workingHrList = working_hr :: all();
          if (count($workingHrList) > 0) {
            return $workingHrList;
        }
        return false;
    }
    
    public function getSubjectiveNamesForValues($values){
         $valuesArray = json_decode($values, true);

            $PoojaDetails = working_hr ::  select('id', 'working_hr')->where('id',$values)
            ->get();
            if (count($PoojaDetails) > 0) {
                return $PoojaDetails;
            }
            return false;
       
    }
}
