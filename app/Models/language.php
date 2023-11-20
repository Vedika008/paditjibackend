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
}
