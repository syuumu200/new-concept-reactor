<?php

namespace App\Listeners;

use App\Events\AssistantCreated;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Entities\Properties\{
    Title,
    Text
};
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Paragraph;
use Illuminate\Support\Str;

class AssistantCreatedLog
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
     * @param  \App\Events\AssistantCreated  $event
     * @return void
     */
    public function handle(AssistantCreated $event)
    {
        $notion = new Notion(config('services.notion.api_token'));
        $page = new Page();
        $page->set("name", Title::value($event->user->username));
        $page->set("id", Text::value($event->user->id));
        $page->set("env", Text::value(config('app.env')));
        $page->set("model", Text::value($event->model));

        $p = $notion->pages()->createInDatabase('8f949ea286e44b7889570575a1a9f61c', $page);

        $text = collect($event->chat)->map(fn ($message) => "<{$message['role']}>\n{$message['content']}")->implode("\n");
        $chunks = preg_split('/(.{1,2000})/us', $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        foreach ($chunks as $chunk) {
            $block = Paragraph::create($chunk);
            $notion->block(collect($p)->toArray()['id'])->append($block);
        }
    }
}
