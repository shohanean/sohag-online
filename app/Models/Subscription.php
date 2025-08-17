<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    public function package()
    {
        return $this->belongsTo(Package::class)->withTrashed();
    }
    public function subscription_fees()
    {
        return $this->hasMany(Subscription_fee::class);
    }
}
