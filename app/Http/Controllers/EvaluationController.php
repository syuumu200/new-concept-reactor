<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function create(Request $request)
    {
        $project = Project::evaluationPercentage()->withCount(['materials', 'evaluations', 'users' => function (Builder $query) {
            $query->select(DB::raw('COUNT(DISTINCT user_id)'));
        }])->findOrFail($request->input('project_id'));
        $user_id = $request->user()->id;
        $material = $project->materials()
            ->whereDoesntHave(
                'evaluations',
                fn (Builder $query) =>
                $query->where('user_id', $user_id)
            )
            ->inRandomOrder()
            ->first();

        return Inertia::render('Evaluation/App', [
            'project' => $project,
            'material' => $material
        ]);
    }

    public function store(Request $request)
    {
        $evaluation = new Evaluation;
        $evaluation->user_id = $request->user()->id;
        $evaluation->material_id = $request->input('material.id');
        $evaluation->value = $request->input('value');
        $evaluation->save();

        return to_route('evaluations.create', [
            'project_id' => $request->input('project_id')
        ]);
    }

    public function reset(Request $request)
    {
        $projectId = $request->input('project.id');
        $userId = $request->user()->id;

        Evaluation::whereHas('material', function (Builder $query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->where('user_id', $userId)->delete();
    }
}
