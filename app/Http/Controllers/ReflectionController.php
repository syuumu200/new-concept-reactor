<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $materials = Material::where('project_id', $request->input('project_id'))->get();

        return Inertia::render('Reflection/App', [
            'materials' => $materials,
            'edges' => DB::table('edges')
                ->whereIn('source', $materials->pluck('id')->toArray())
                ->whereIn('target', $materials->pluck('id')->toArray())
                ->get(),
            'suggestion' => Inertia::lazy(fn () => $this->suggestion($request->input('project_id'), $materials)),
        ]);
    }

    public function suggestion($project_id, $materials)
    {
        $project = Project::findOrFail($project_id);

        $content = <<<EOD
あなたは発想支援や意見集約を行うシステム New ConceptReactorにおけるファシリテーターです。
意見の集約，意見への投票のプロセスを経て，最後となる”意見の振り返り”を行うところです。

【プロジェクトの名前】
{$project->name}

【プロジェクトの概要】
{$project->description}

【ファシリテーター設定】
{$project->facilitator}

【出力の内容】
1. システムとファシリテーターに関する自己紹介
2. 最終プロセスである”意見の振り返り”の説明
1. 意見の一覧または意見を整理したもの
2. 整理された意見に対しての考察
3. ファシリテーターとしての逆説的な意見
4. 考察と逆説的な意見に対する結論
5. プロジェクト参加者への謝辞

【出力時のルール】
・出力する情報には識別子を含めないでください。

【登録された意見の一覧】

意見は影響を介した階層構造を成しており，世代を重ねるごとに当初の意見とは違ったものに変化していく事が期待されています。
EOD;
        $materials->load('sources', 'user')->each(function ($material) use (&$content) {
            $fields = [
                '識別子' =>  $material->id,
                '発案者' =>  $material->user->username,
                '内容' =>  $material->body,
                '高評価数' =>  $material->evaluations->where('value', 1)->count(),
                '低評価数' =>  $material->evaluations->where('value', -1)->count(),
            ];
            if ($material->sources->isNotEmpty()) {
                Arr::add($fields, '影響源', $material->sources[0]->id);
                Arr::add($fields, '影響源', $material->sources[1]->id);
            }
            $content .= "\n```\n";
            foreach ($fields as $key => $value) {
                $content .= "$key=$value\n";
            }
            $content .= "```";
        });

        //dd($content);

        $result = OpenAI::chat()->create([
            'model' => 'gpt-4-1106-preview',
            'messages' => [
                [
                    "role" => "system",
                    "content" => $content
                ]
            ],
        ]);

        /*
        if (isset(json_decode($chat)->error)) {
            $suggestion = "エラーが発生しました。";
        }
        */

        $suggestion = $result->choices[0]->message->content;

        //dd($suggestion);

        return $suggestion;
    }
}
