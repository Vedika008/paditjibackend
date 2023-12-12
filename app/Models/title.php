<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class title extends Model
{
    protected $table = "title";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title_name'
    ];

    public function gettitleList()
    {
        $titleList = title::all();
        if (count($titleList) > 0) {
            return $titleList;
        }
        return false;
    }
    public static function getSubjectiveNames()
    {
        $title = self::all(['id', 'title_name'])->pluck('title_name', 'id')->toArray();

        return $title;
    }

    public function getSubjectiveNamesForValues($values)
    {

        if($values != 'other'){
            // $valuesArray = json_decode($values, true);
            // dd($valuesArray);
            // $titleDetails = title :: where('id',$valuesArray)->get();
            $titleDetails = title::where('id', $values)->get();
            
            // if (in_array('other', $valuesArray)) {
            //     array_push($titleDetails, ['id' => 'other', 'title_name' => 'Other']);
            // }
            
            if (count($titleDetails) > 0) {
                return $titleDetails;
            }
            return false;
        }

        return[['id'=> 'other', 'title_name'=> "Other"]];

    }
}
