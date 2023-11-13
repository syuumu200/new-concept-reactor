<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
    public function store(Request $request)
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
<ファシリテーターの語尾>
{$request->input('facilitator.endOfSentence')}
<ファシリテーターの性格>
{$request->input('facilitator.character')}
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
    public function show(Project $project)
    {

        return Inertia::render('Project/Show', [
            'project' => $project->load('user', 'materials.user')->loadCount('materials')
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
        return Inertia::render('Project/Edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
