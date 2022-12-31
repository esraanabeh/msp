<?php

namespace Modules\Templates\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Organization\Models\Organization;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;


class Template extends Model
{
    use HasFactory ,LogsActivity, SoftDeletes;
    protected static $logAttributes =['title','description','due_date','is_completed'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'title',
        'organization_id',
        'description',
        'due_date',
        'is_completed',
        'owner_id',
        'type',
        'created_by'
        ];


        public function organization()
        {
            return  $this->belongsTo(Organization::class, 'organization_id', 'id');
        }


        public function sections()
        {
            return  $this->hasMany(Section::class, 'template_id', 'id');
        }

        public function member()
        {
            return  $this->hasMany(Member::class, 'template_id', 'id');
        }


        public function user()
        {
            return  $this->belongsTo(User::class, 'created_by');
        }

        public function template_client()
        {
            return $this->hasMany(TemplateClient::class, 'template_id','id');
        }


        public function scopeSections($query, bool $value = true, $filter = null,$is_member=false)
        {

             $query->whereHas('sections', function ($query) use ($value, $filter, $is_member){
                $query->when($is_member,function($query){
                    $query->whereHas('team',function($query){
                        $query->whereHas('members',function($query){
                            $query->where('user_id',auth()->user()->id);
                        });
                    });
                })


                ->whereHas('client_sections',function($query)use ($value, $filter, $is_member){
                    $query->where('is_completed', $value)

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
                });

            });

        }


           /**
     * @param $query
     * @param $title
     * @return void
     */
    public function scopeSearchTitleTemplate($query, $title)
    {
        $check = $title && $title != "";

        $query->when($check, function($query) use($title) {

            $query->where('title','LIKE', '%'. $title .'%');

        });
    }


}
