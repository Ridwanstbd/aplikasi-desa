<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsRotation extends Model
{
    use HasFactory;

    protected $fillable = ['current_cs_id'];
}
