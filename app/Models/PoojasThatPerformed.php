<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class PoojasThatPerformed extends Model
{
    protected $table = "pooja_performed";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'pooja_name'
    ];

    public function getPoojalist()
    {
        $PoojaList = PoojasThatPerformed :: all();
        if (count($PoojaList) > 0) {
            return $PoojaList;
        }
        return false;
    }
}
