<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointmentDetails extends Model
{
    use HasFactory;
    protected $table = 'appointment_creation';
    protected $primaryKey = 'id';

    protected $fillable = [
        'yajman_name', 'yajman_mobile_no', 'state', 'city', 'address', 'date', 'pooja', 'other_pooja', 'pooja_material', 'created_at', 'updated_at','created_by'
    ];

    public function getAppointmentDetails($panditId){
        $yajmanDetails = appointmentDetails::where('id', $panditId)->get()->all();

        if (count($yajmanDetails) > 0) {
            return $yajmanDetails;
        } else {
            return false;
        }


    }
}
