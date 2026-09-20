<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{   
    // columns that can be inserted manually
    protected $fillable = [
        "key",
        "expires_at"
    ];

    public function token()
    {
        // Key table has many 'token' because I add foreign key to token table
        return $this->hasOne(AuthToken::class); 
    }
}
