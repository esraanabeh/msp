<?php

namespace Modules\Templates\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Models\Client;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;

class ClientSection extends Model
{
    use HasFactory, LogsActivity;
    protected static $logAttributes =['is_completed',
    'due_date',
    'progress',
    'section_id',
    'client_id'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $table = 'sections_clients';

    protected $fillable = [
        'is_completed',
        'due_date',
        'progress',
        'section_id',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class , 'client_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class , 'section_id')->withTrashed();
    }


    public function scopeSections($query, bool $value = true, $filter = null)
    {
               $query->when($filter, function ($query) use ($filter){

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

          /**
     * @param $query
     * @param $title
     * @return void
     */
    public function scopeSearchClientOrTemplate($query, $title)
    {
        $check = $title && $title != "";

       $query->whereHas('section',function($query) use($check,$title) {
            $query->whereHas('template',function($query) use($check,$title) {
                $query->when($check, function($query) use($check,$title) {

                    $query->where('title','LIKE', '%'. $title .'%');

                });
            });
        })
        ->orWhereHas('client',function($query)use ($title, $check){
            $query ->when($check, function($query) use ($title, $check){
                $query->where(function ($query) use ($title, $check){
                    $query->where('contact_person','LIKE', '%'.$title.'%');
                });
            });
        });


    }

         /**
     * @param $query
     * @param $title
     * @return void
     */
    public function scopeSearchCompanyName($query, $name){
        $check = $name && $name != "";
        $query->whereHas('client',function($query)use ($name, $check){
            $query ->when($check, function($query) use ($name, $check){
                $query->where(function ($query) use ($name, $check){
                    $query->where('contact_person','LIKE', '%'.$name.'%');
                });
            });
        });

    }

}
