<!DOCTYPE html>
<html>

<head>
    <title>ChatGPTによる振り返り結果</title>
</head>

<body>
    <h1>ChatGPTによる振り返り結果</h1>

    <p>【プロジェクト名】</p>
    <p>{{ $project->name }}</p>
    <p>【プロジェクト設定】</p>
    <div>{!! nl2br(e($project->description)) !!}</div>
    <hr>
    <p>【ファシリテーター設定】</p>
    <div>{!! nl2br(e($project->facilitator)) !!}</div>
    <hr>
    <p>【状況】</p>
    <p>参加者数：{{ $project->distinct_users_count }}</p>
    <p>登録意見数：{{ $project->materials_count }}</p>
    <p>評価率：{{ $project->evaluation_percentage }}%</p>
    <hr>
    <p>【振り返り結果】</p>
    <div>{!! nl2br(e($reflection)) !!}</div>
</body>

</html>