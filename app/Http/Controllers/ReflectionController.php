<?php

namespace App\Http\Controllers;

use App\Events\ReflectionCreated;
use App\Models\Material;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;

class ReflectionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $project = Project::distinctUsersCount()
            ->evaluationPercentage()
            ->with('materials.sources', 'materials.evaluations', 'user')
            ->withCount('materials')
            ->findOrFail($request->input('project_id'));

        return Inertia::render('Reflection/App', [
            'project' => $project,
            'materials' => $project->materials,
            'edges' => DB::table('edges')
                ->whereIn('source', $project->materials->pluck('id')->toArray())
                ->whereIn('target', $project->materials->pluck('id')->toArray())
                ->get(),
            'suggestion' => Inertia::lazy(fn () => $this->suggestion($project)),
        ]);
    }

    public function suggestion(Project $project)
    {
        $propmpts = collect();
        $propmpts->push(
            <<<EOD
あなたは発想支援や意見集約を行うシステム New ConceptReactorにおけるファシリテーターです。
意見の集約，意見への評価のプロセスを経て，最後となる”意見の振り返り”を行うところです。

【プロジェクトの名前】
{$project->name}

【プロジェクトの概要】
{$project->description}

【ファシリテーター設定】
{$project->facilitator}

【プロジェクトの状況】
参加ユーザー数：$project->distinct_users_count
登録された意見の数：$project->materials_count
意見の評価率：$project->evaluation_percentage%

【出力の内容】
1. システムとファシリテーターに関する自己紹介
2. 最終プロセスである”意見の振り返り”の説明
3. 状況の報告
4. 意見の一覧
5. 意見の整理・分類
6. 5をふまえた考察
7. 評価をふまえた考察
8. 影響をふまえた考察
9. 6と7と8をふまえた考察
10. ファシリテーターとしての逆説的な意見
11. 9と10をふまえた結論
12. プロジェクト参加者への謝辞

【出力時のルール】
・出力する情報には識別子を含めないでください。

【登録された意見の一覧】

意見は影響を介した階層構造を成しており，世代を重ねるごとに当初の意見とは違ったものに変化していく事が期待されています。
EOD
        );

        $project->materials->map(function ($material) {
            $fields = Arr::map([
                '識別子' => $material->id,
                '発案者' => $material->user->username,
                '内容' => $material->body,
                '高評価数' => $material->evaluations->where('value', 1)->count(),
                '低評価数' => $material->evaluations->where('value', -1)->count(),
                '影響源' => $material->sources->isNotEmpty() ? $material->sources->implode('id', ',') : '無し'
            ], function ($field, $key) {
                return "$key=$field";
            });
            return Arr::join($fields, "\n");
        })->each(fn ($material) => $propmpts->push($material));

        $result = OpenAI::chat()->create([
            'model' => 'gpt-4-1106-preview',
            'messages' => [
                [
                    "role" => "system",
                    "content" => $propmpts->implode("\n\n")
                ]
            ],
        ]);

        $messages = [
            [
                "role" => "system",
                "content" => $propmpts->implode("\n\n")
            ],
            [
                "role" => "assistant",
                "content" => $result->choices[0]->message->content
            ]
        ];
        ReflectionCreated::dispatch(
            Auth::user(),
            $messages,
            $result->meta()->openai->model
        );

        $suggestion = $result->choices[0]->message->content;

        return $suggestion;
    }
}
