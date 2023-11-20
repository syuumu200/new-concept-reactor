<?php

namespace App\Listeners;

use App\Events\ReflectionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Entities\Properties\{
    Title,
    Text
};
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Paragraph;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ReflectionCreatedLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ReflectionCreated  $event
     * @return void
     */
    public function handle(ReflectionCreated $event)
    {
        $notion = new Notion(config('services.notion.api_token'));
        $page = new Page();
        $page->set("name", Title::value($event->user->username));
        $page->set("user_id", Text::value($event->user->id));
        $page->set("env", Text::value(config('app.env')));
        $page->set("model", Text::value($event->model));

        $p = $notion->pages()->createInDatabase('9c08f9e0407641a4812536251a437dcc', $page);

        $text = collect($event->messages)->map(fn ($message) => "<{$message['role']}>\n{$message['content']}")->implode("\n");
        $chunks = preg_split('/(.{1,2000})/us', $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        foreach ($chunks as $chunk) {
            $block = Paragraph::create($chunk);
            $notion->block(collect($p)->toArray()['id'])->append($block);
        }
    }
}
