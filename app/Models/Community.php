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
        'id', 'community_name'
    ];

    public function getCommunityList()
    {
        $ComunityList = Community :: all();
          if (count($ComunityList) > 0) {
            return $ComunityList;
        }
        return false;
    }
}
