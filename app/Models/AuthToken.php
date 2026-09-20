<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    protected $fillable = [
        'token',
        'expires_at'
    ];

    public function key()
    {
        // token column belongs to key_id 
        return $this->belongsTo(Key::class);
    }
}
