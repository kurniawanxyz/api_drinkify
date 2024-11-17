<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends BaseModel
{
    /** @use HasFactory<\Database\Factories\NotificationFactory> */
    use HasFactory;

    public function user()
    {
        return $this->hasMany(User::class);
    }

}
