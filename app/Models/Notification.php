<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['title', 'message', 'user_id', 'is_read', 'delivery_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
