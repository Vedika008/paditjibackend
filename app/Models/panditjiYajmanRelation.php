<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class panditjiYajmanRelation extends Model
{
    use HasFactory;
    protected $table = 'panditji_yajman_relation';
    protected $primaryKey = 'id';

    protected $fillable = [
         'pantiji_id', 'yajman_id', 'created_at', 'updated_at', 'created_by'
    ];

    public function paditjiYajmanRelationExist($panditjiId,$yajmanId){
        $exist = panditjiYajmanRelation :: where ('pantiji_id',$panditjiId) ->where ('yajman_id',$yajmanId)
        ->orderBy('created_at', 'desc')
        ->exist();

        if($exist){
            return true;
        }else{
            return false;
        }
    }
}
