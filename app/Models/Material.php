<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Models\User;

class Material extends Model
{
    use HasFactory, HasUlids;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
