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

    public function getPoojalist($id)
    {
        $poojaList = newPooja::select('id', 'pooja_name', 'pooja_material')
        ->where('created_by',$id)
        ->orderBy('created_at', 'desc') 
        ->get();

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

    public function getPoojaById($Panditjiid,$id){

        $pooja = newPooja :: where('created_by',$Panditjiid)->where('id',$id)->get();

         if (count($pooja) > 0) {
            $decodedPoojaList = [];
            foreach ($pooja as $pooja) {
                $decodedPooja = $pooja->toArray(); 
                $decodedPooja['pooja_material'] = json_decode($pooja->pooja_material, true); 
                $decodedPoojaList[] = $decodedPooja;
                
            }
            return $decodedPoojaList;
        }else{
            return false;
        }
    }

    public static function getSubjectiveNames()
    {
        $communities = self::all(['id', 'pooja_name'])->pluck('pooja_name', 'id')->toArray();

        return $communities;
    }

    public  function getSubjectiveNamesForValues($values)
    {
        $valuesArray = json_decode($values, true);

        $poojaAppointmentExistOrNOt = newPooja::exists($values);
    
        if($poojaAppointmentExistOrNOt){
            $PoojaDetails = newPooja ::  select('id','pooja_name')
            ->where('created_by',$valuesArray)->get();
            if (count($PoojaDetails) > 0) {
                return $PoojaDetails;
            }
            return false;
        }else{
            return [];
        }       
    }
    public function IsPuja($panditjiId, $id){
        $IsPuja = newPooja ::where('created_by', $panditjiId)->where('id', $id)->first();
        return $IsPuja; 
    
    }


}
