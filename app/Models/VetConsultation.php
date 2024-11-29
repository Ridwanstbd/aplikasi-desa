<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VetConsultation extends Model
{
    protected $fillable = ['full_name', 'address', 'phone_number', 'consultation_date', 'notes'];
}
