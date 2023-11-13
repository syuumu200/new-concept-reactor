<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Models\{
    User,
    Project,
    Evaluation
};

class Material extends Model
{
    use HasFactory, HasUlids;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function sources()
    {
        return $this->belongsToMany(self::class, 'edges', 'target', 'source');
    }
}
