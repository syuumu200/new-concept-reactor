<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

class MaterialController extends Controller
{
    public function create(Request $request)
    {
        $project = Project::withCount('materials')->findOrFail($request->input('project_id'));
        $now = now();

        $content = '';

        if ($request->session()->missing("projects.$project->id")) {
            $content .= <<<EOD
あなたは集団での意見集約や発想支援を行うシステム New ConceptReactorにおけるファシリテーターです。


【ログイン中のユーザー情報】
ユーザー名：{$request->user()->username}

【ファシリテーションプロセス】
New ConceptReactorのプロジェクトは以下のプロセスを経ます。プロセスを進展させるには，後述するプロセスの進展条件を達成する必要があります。

1. 意見の登録 - 無から意見を考える段階です。
2. 意見の交差 - システムがランダムに選出した２つの意見を参考に意見を考える段階です。交差という言葉の出典は，アメリカの心理学者，ユージン・ジェンドリンが提唱したフォーカシングという手法にあります。ここでは，参考にすべき意見をシステムが選定しユーザーに示す事を交差と呼んでいます。無造作な交差により豊かな発想を支援します。
3. 意見への投票 - 意見に対して投票します。この時点でも意見の登録ができます。
4. 意見の振り返り - ChatGPTが意見を整理し，考察を行います。この時点でも意見の登録ができます。また，意見への投票も可能です。

今回ファシリテーションを行うプロジェクトです。

【プロジェクトの名前】
$project->name
【プロジェクトのコンセプト】
$project->description

【ファシリテーター設定】
$project->facilitator

【プロセスの進展条件】
交差開始意見数：$project->cross_start
投票開始意見数：$project->vote_start
意見振り返り開始意見数：$project->reflection_start    

【ファシリテーターの役割】
・New ConceptReactorの意見の集約や発想支援について説明します。
・プロジェクトについて説明します。
・プロジェクトの概要やファシリテーションプロセスに従い，ユーザーがその時点で何をすべきか指示しなければなりません。
・ユーザーが入力に迷わないように，様々な入力例を示してください。
・ファシリテーターの指示が絶対的でない事を伝えなければなりません。ファシリテーターの例示とは関係のない意見を登録する事も推奨してください。
EOD;
        }

        $content .= <<<EOD
        
【{$now}時点でのプロジェクトの状況】
登録された意見の数：$project->materials_count
EOD;
        if ($project->materials_count >= $project->cross_start) {
            $sources = $project->materials
                ->crossJoin($project->materials)
                ->reject(fn ($symbols) => $symbols[0]->body === $symbols[1]->body)
                ->random();

            $content .= <<<EOD

【意見の交差】
登録されたものの中からランダムに「{$sources[0]->body}」と「{$sources[1]->body}」を選出しました。
これらの意見をユーザーに参考にしてもらい，更に良い意見を考えるよう促してください。 
EOD;
        }

        $request->session()->push("projects.$project->id", [
            'role' => 'system',
            'content' => $content
        ]);

        if (collect($request->session()->get("projects.$project->id"))->last()['role'] !== 'assistant') {
            $this->generateChat($request, $project);
        }

        return Inertia::render('Material/Create', [
            'project' => $project,
            'sources' => $sources ?? [],
            'messages' => collect($request->session()->get("projects.$project->id"))->filter(fn ($message) => $message['role'] !== 'system')->values()->toArray()
        ]);
    }

    public function generateChat($request, $project)
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4-1106-preview',
            'messages' => $request->session()->get("projects.$project->id")
        ]);

        /*
        if (isset(json_decode($chat)->error)) {
            $request->session()->push("projects.$project->id", [
                'role' => 'assistant',
                'content' => 'エラーが発生しました。'
            ]);
        }
        */

        $request->session()->push("projects.$project->id", [
            'role' => 'assistant',
            'content' => $result->choices[0]->message->content
        ]);
    }

    public function store(Request $request)
    {
        $user_id = $request->user()->id;
        $project_id = $request->input('project_id');
        $suggestion = $request->input('suggestion');
        $sources = $request->collect('sources');
        $targets = $request->collect('targets');
        $now = now();

        $suggestion = Suggestion::firstOrCreate(['body' => $suggestion]);
        //dd($suggestion->id);

        foreach ($targets as $target) {
            //dd($target);
            $request->session()->push("projects.$project_id", [
                'role' => 'user',
                'content' => $target['body']
            ]);
        }

        $materials = $targets->map(
            fn ($material)  =>
            [
                'id' => (string) Str::ulid(),
                'user_id' =>  $user_id,
                'project_id' =>  $project_id,
                'suggestion_id' => $suggestion->id,
                'body' => $material['body'],
                'created_at' => $now,
                'updated_at' => $now
            ]
        );

        DB::table('materials')->insert($materials->toArray());
        DB::table('edges')->insert($sources->crossJoin($materials)->map(fn ($edge) => [
            'source' => $edge[0]['id'],
            'target' => $edge[1]['id'],
        ])->toArray());


        return to_route('materials.create', [
            'project_id' => $request->input('project_id')
        ]);
    }

    public function forget(Request $request)
    {
        $request->session()->forget("projects.{$request->input('project_id')}");
        /*
        return to_route('materials.create', [
            'project_id' => $request->input('project_id')
        ]);
        */
    }
}
