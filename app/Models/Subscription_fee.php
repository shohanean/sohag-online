<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Subscription_fee extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by', 'id');
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }
    public function getDueDateStatusAttribute()
    {
        if (!$this->due_date) {
            return ['text' => 'No Due Date', 'class' => 'secondary'];
        }

        $dueDate = Carbon::parse($this->due_date);
        $today   = Carbon::today();

        $daysLeft = $today->diffInDays($dueDate, false);

        if ($daysLeft > 0) {
            return ['text' => $daysLeft . ' days left', 'class' => 'success']; // green
        } elseif ($daysLeft === 0) {
            return ['text' => 'Due today', 'class' => 'warning']; // orange
        } else {
            return ['text' => abs($daysLeft) . ' days overdue', 'class' => 'danger']; // red
        }
    }
}
