<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class language extends Model
{
    protected $table = "Language";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'language_name'
    ];

    public function getLanguageList()
    {
        $languageList = language :: all();
          if (count($languageList) > 0) {
            return $languageList;
        }
        return false;
    }

    public static function getSubjectiveNames()
    {
        $communities = self::all(['id', 'language_name'])->pluck('language_name', 'id')->toArray();

        return $communities;
    }

    public  function getSubjectiveNamesForValues($values)
    {
        $valuesArray = json_decode($values, true);
        $languageDetails= language::whereIn('id', $valuesArray)->get();
        if(in_array('other', $valuesArray)){
            $languageDetails = $languageDetails->toArray();
            array_push($languageDetails, ['id'=> 'other', 'community_name'=> 'Other']);
        }

        if (count($languageDetails) > 0) {
            return $languageDetails;
        }
        return false;

    }
}
