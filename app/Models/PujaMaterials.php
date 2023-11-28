<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class PujaMaterials extends Model
{
    protected $table = "pooja_materials";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'pooja_material_name' 
    ];

    public function getpoojaMaterialList()
    {
        $PoojaList = PujaMaterials :: all();
        if (count($PoojaList) > 0) {
            return $PoojaList;
        }
        return false;
    }
}
