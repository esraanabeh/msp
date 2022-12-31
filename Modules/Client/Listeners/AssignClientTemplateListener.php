<?php

namespace Modules\Client\Listeners;

use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\Client\Events\AssignClientTemplate;
use Modules\Tasks\Models\ClientTask;
use Modules\Templates\Models\ClientSection;
use Modules\Templates\Models\Template;

class AssignClientTemplateListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(AssignClientTemplate $event)
    {
        $template = Template::find($event->templateId);
        foreach($template->sections as $section){
                $clientSection = new ClientSection();
                $clientSection->section_id =  $section->id;
                $clientSection->client_id  = $event->clientId;
                $clientSection->due_date   = Carbon::now();
                $clientSection->save();

            foreach($section->tasks as $task){
                ClientTask::create([
                    'task_id' =>  $task->id,
                    'client_id' => $event->clientId
                ]);
            }
        }
    }
}
