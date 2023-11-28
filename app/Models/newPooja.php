<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class newPooja extends Model
{
    protected $table = "poojaCreation";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // id, pooja_name, pooja_material, created_at, updated_at, created_by, materialid, materialName, materialQuantity
        'id', 'pooja_name', 'pooja_material', 'created_at', 'updated_at' ,'created_by' 
    ];

    public function getPoojalist()
    {
              
        $poojaList = newPooja::select('id', 'pooja_name', 'pooja_material')->get();

    if (count($poojaList) > 0) {
        $decodedPoojaList = [];

        foreach ($poojaList as $pooja) {
            $decodedPooja = $pooja->toArray(); 
            $decodedPooja['pooja_material'] = json_decode($pooja->pooja_material, true); 
            $decodedPoojaList[] = $decodedPooja;
            
        }
        return $decodedPoojaList;
    }

    return false;
    }
}
