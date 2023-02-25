<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyViolations extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $cast = ['created_at' => 'date:Y-m-d'];
    
}
