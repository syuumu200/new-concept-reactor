<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjecrCreateRequest;
use App\Http\Requests\ProjectReqeust;
use App\Http\Requests\ProjectUpdateReqeust;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render(
            'Project/Index',
            ['projects' => Project::with('user')->latest()->get()]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Project/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjecrCreateRequest $request)
    {
        $project = new Project;
        $project->user_id = $request->user()->id;
        $project->name = $request->input('name');
        $project->description = <<<EOD
<プロジェクト概要>
{$request->input('description')}
<プロジェクトでユーザーから集めるもの>
{$request->input('collectibles')}
EOD;
        $project->facilitator = <<<EOD
<ファシリテーターの名前>
{$request->input('facilitator.name')}
<ファシリテーターの一人称>
{$request->input('facilitator.firstPerson')}
<ユーザーを呼ぶときの敬称>
{$request->input('facilitator.honorificTitle')}
<ファシリテーターの性格>
{$request->input('facilitator.character')}
<ファシリテーターの語尾>
{$request->input('facilitator.endOfSentence')}
<ファシリテーターの口癖>
{$request->input('facilitator.favouritePhrase')}
EOD;
        $project->cross_start = $request->input('cross_start');
        $project->vote_start = $request->input('vote_start');
        $project->reflection_start = $request->input('reflection_start');
        $project->save();

        return to_route('projects.show', $project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show($projectId)
    {
        return Inertia::render('Project/Show', [
            'project' => Project::where('id', $projectId)->evaluationPercentage()->first()->load('user', 'materials.user')->loadCount(['materials', 'evaluations', 'users' => function (Builder $query) {
                $query->select(DB::raw('COUNT(DISTINCT user_id)'));
            }]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return Inertia::render('Project/Edit', [
            'project' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectUpdateReqeust $request, Project $project)
    {
        $project->name = $request->input('name');
        $project->description = $request->input('description');
        $project->facilitator = $request->input('facilitator');
        $project->cross_start = $request->input('cross_start');
        $project->vote_start = $request->input('vote_start');
        $project->reflection_start = $request->input('reflection_start');
        $project->save();
        return to_route('projects.show', $project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        Project::destroy($project->id);
        return to_route('projects.index');
    }
}
