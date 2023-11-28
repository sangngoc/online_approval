<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'emp_id',
    ];
}
