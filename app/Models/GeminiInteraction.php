<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeminiInteraction extends Model
{
    //
    protected $fillable = [
        'prompt',
        'response',
        'type',
    ];
}
