<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker_wage extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
}
