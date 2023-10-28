<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Orhanerday\OpenAi\OpenAi;

class MaterialController extends Controller
{
    public function create(Request $request)
    {
        $project = Project::findOrFail($request->input('project_id'));
        $materials = $project->materials;


        if ($materials->count() > $project->convergence_start) {
            $symbol = $materials->crossJoin($materials)->random();
            $content = <<<EOD
            あなたは発想支援や意見集約を行うシステム New ConceptReactorにおけるファシリテーターとして，
            「{$project->description}」という説明を持つ次のプロジェクト「{$project->name}」において集約された意見を整理する必要があります。

            実際に登録された意見は以下の通りです。
EOD;
            $materials->each(function ($material, $key) use (&$content) {
                $content .= "「{$material->body}」\n";
            });

            $content .= "既に十分な意見が登録されていますが，ユーザーは新たに意見を登録することもできます。ファシリテーターの立場から意見を提案してもかまいません。";
        } elseif ($materials->count() > $project->cross_start) {
            $symbol = $materials->crossJoin($materials)->random();
            $content = <<<EOD
            あなたは発想支援や意見集約を行うシステム New ConceptReactorにおけるファシリテーターとして，
            「{$project->description}」という説明を持つ次のプロジェクト「{$project->name}」における意見の集約をユーザーに促してください。
            現在は{$materials->count()}個の意見が登録されています。
            
            登録されたものの中に「{$symbol[0]->body}」と「{$symbol[1]->body}」の意見がありました。この２つの意見をユーザーに示し，２つの意見を参考にした更に良い意見を考えるよう促してください。 
EOD;
        } else {
            $content = <<<EOD
            あなたは発想支援や意見集約を行うシステム New ConceptReactorにおけるファシリテーターとして，
            「{$project->description}」という説明を持つ次のプロジェクト「{$project->name}」における意見の集約をユーザーに促してください。
            また，最初にあなた自身についての紹介を行ってください。ただし，すでに意見が登録されている場合は利用された事があるという事なので自己紹介を行う必要はありません。

            現在は{$materials->count()}個の意見が登録されていますが，登録された意見は全体に公開されません。
EOD;
        }
        $open_ai = new OpenAi(config('services.chat-gpt.key'));
        $chat = $open_ai->chat([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    "role" => "system",
                    "content" => $content
                ]
            ],
            'temperature' => 1.0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);

        if (isset(json_decode($chat)->error)) {
            $message = "エラーが発生しました。";
        }

        $message = json_decode($chat)->choices[0]->message->content;

        return Inertia::render('Material/Create', [
            'project' => $project,
            'message' => $message
        ]);
    }

    public function store(Request $request)
    {
        $material = new Material;
        $material->user_id = $request->user()->id;
        $material->project_id = $request->input('project_id');
        $material->body = $request->input('body');
        $material->save();

        return to_route('materials.create', [
            'project_id' => $request->input('project_id')
        ]);
    }
}
