<?php

namespace App\Console\Commands;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Templates\Notifications\SendTaskReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\Templates\Models\Section;
use Modules\Templates\Notifications\SectionReminder;

class TaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send email notifications to users to remind them of their tasks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sections = Section::where('automatic_reminder',1)
                            ->whereHas('template',function($q){
                                $q->has('template_client','>',0);
                            })
                            ->whereHas('client_sections',function($qq){
                                $qq->where('progress','<',100);
                                $qq->where('is_completed',0);
                            })
                            ->whereDate('next_reminder','=',today())->with('team')->get();

        foreach($sections as $section){
            $users = User::WhereHas('member',function($q) use ($section){
                $q->where('team_id',$section->team->id);
            })->get();

            foreach($users as $user){
                $user->notifyAt(new SectionReminder($section), new Carbon($section->next_reminder));
            }

            $section->update([
                'next_reminder' => now()->addDays($section->reminder_day)->setTimeFromTimeString($section->reminder_time)
            ]);
        }
    }
}
