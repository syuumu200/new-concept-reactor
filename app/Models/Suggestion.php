<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Suggestion extends Model
{
    use HasFactory, HasUlids;
    
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'body'
    ];
}
