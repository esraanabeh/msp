<?php

namespace Modules\Tasks\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\Client\Models\Client;
use Modules\Tasks\Models\Task;
use Modules\Templates\Models\Section;
use Modules\Tasks\Events\sectionProgress;
use Modules\Templates\Models\ClientSection;

class sectionProgressListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(sectionProgress $event)
    {
        $section = ClientSection::where('section_id',$event->task->task->section_id)
                                ->where('client_id',$event->task->client_id)
                                ->first();

        $client = Client::find($event->task->client_id);

        $tasks = $client->tasks()->whereHas('task',function($q) use ($section){
            $q->where('section_id',$section->section_id);
        })->get();

        $completedTasks = collect($tasks)->where('done',1)->count();
        $totalTasks = $tasks->count();

        $sectionProgress = ($completedTasks / $totalTasks) * 100;


        if($sectionProgress == 100){
            $section -> update([
                'progress' => $sectionProgress,
                'is_completed' => 1
            ]);
        } else {
            $section->update([
                'progress' => $sectionProgress,
                'is_completed' => 0
                ]);
        }
    }
}
