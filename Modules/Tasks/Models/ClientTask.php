<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Models\Client;
use Modules\Templates\Models\ClientSection;
use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientTask extends Model implements HasMedia
{
    use HasFactory , LogsActivity;
    use InteractsWithMedia;
    protected static $logAttributes =[ 'status',
    'answer',
    'done',];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

protected $table = 'tasks_clients';

    protected $fillable = [
        'status',
        'answer',
        'done',
        'task_id',
        'client_id'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class , 'task_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }


    public function scopeTasks($query, bool $value = true, $filter = null)
    {
            $query->where('done', $value)

                ->when($filter, function ($query) use ($filter){

                $query->when($filter === 'this-month', function ($query){

                    $query->whereMonth('created_at', now()->month);

                })->when($filter === 'this-week', function ($query){

                    $query->whereBetween('created_at',
                        [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);

                })->when($filter === 'this-year', function ($query){

                    $query->whereYear('created_at', now()->year);

                });
            });

    }

}
