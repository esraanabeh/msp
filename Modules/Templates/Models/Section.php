<?php

namespace Modules\Templates\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Team\Models\Team;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Tasks\Models\Task;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Section extends Model
{
    use HasFactory,LogsActivity, SoftDeletes;
    protected static $logAttributes =[ 'name',
    'template_id',
    'team_id',
    'description',
    'due_date',
    'reminder_day',
    'reminder_time',
    'is_completed',
    'automatic_reminder',
    'shared_with_client'];

    protected $fillable = [
        'name',
        'template_id',
        'team_id',
        'description',
        'due_date',
        'reminder_day',
        'reminder_time',
        'is_completed',
        'automatic_reminder',
        'shared_with_client',
        'progress',
        'next_reminder'
        ];


    public function template()
    {
        return  $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function team()
    {
        return  $this->belongsTo(Team::class, 'team_id', 'id');
    }


    public function tasks()
    {
        return  $this->hasMany(Task::class, 'section_id', 'id');
    }



    public function emailTemplate()
    {
        return $this->hasOne(SectionEmailTemplate::class);
    }

    // protected function dueDate(): Attribute
    // {
    //     return  new Attribute(get:fn ($value) =>  is_null($value) ? NULL : Carbon::parse($value)->format('d/m/Y'));
    // }

    public function client_sections()
    {
        return $this->hasMany(ClientSection::class);
    }
}
