<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class Community extends Model
{
    protected $table = "Community";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'community_name'
    ];

    public function getCommunityList()
    {
        $ComunityList = Community::all();
        if (count($ComunityList) > 0) {
            return $ComunityList;
        }
        return false;
    }
    public static function getSubjectiveNames()
    {
        $communities = self::all(['id', 'community_name'])->pluck('community_name', 'id')->toArray();

        return $communities;
    }

    public function getSubjectiveNamesForValues($values)
    {
        $valuesArray = json_decode($values, true);
        // $communityDetails = Community :: where('id',$valuesArray)->get();
        $communityDetails = Community::whereIn('id', $valuesArray)->get();
        if(in_array('other', $valuesArray)){
            $communityDetails = $communityDetails->toArray();
            array_push($communityDetails, ['id'=> 'other', 'community_name'=> 'Other']);
        }
        if (count($communityDetails) > 0) {
            return $communityDetails;
        }
        return false;

    }
}
