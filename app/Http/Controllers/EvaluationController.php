<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Material;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;

class EvaluationController extends Controller
{
    public function create(Request $request)
    {
        $user_id = $request->user()->id;
        $material = Material::where('project_id', $request->input('project_id'))
            ->whereDoesntHave('evaluations', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->inRandomOrder()
            ->first();
        if ($material === null) {
            return to_route('projects.show', $request->input('project_id'));
        }
        return Inertia::render('Evaluation/App', [
            'project_id' => $request->input('project_id'),
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

        return to_route('evaluation', [
            'project_id' => $request->input('project_id')
        ]);
    }
}
