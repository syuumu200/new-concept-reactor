<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Models\{
    User,
    Material
};
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory, HasUlids;

    protected $casts = [
        'evaluation_percentage' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Material::class,
            'project_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function evaluations()
    {
        return $this->hasManyThrough(Evaluation::class, Material::class);
    }

    public function scopeEvaluationPercentage($query)
    {
        return $query->addSelect(['evaluation_percentage' => function ($query) {
            $query->selectRaw('COALESCE(ROUND((COUNT(evaluations.id) / (SELECT COUNT(DISTINCT users.id) * COUNT(DISTINCT materials.id) FROM materials JOIN users ON users.id = materials.user_id WHERE materials.project_id = projects.id)) * 100), 0)')
                ->from('evaluations')
                ->join('materials', 'materials.id', '=', 'evaluations.material_id')
                ->whereColumn('materials.project_id', 'projects.id');
        }]);
    }

    public function scopeDistinctUsersCount($query)
    {
        return $query->addSelect(['distinct_users_count' => Material::select(DB::raw('count(distinct user_id)'))
            ->whereColumn('project_id', 'projects.id')
        ]);
    }
}
