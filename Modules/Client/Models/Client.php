<?php

namespace Modules\Client\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Organization\Models\Organization;
use Modules\Opportunity\Models\Opportunity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Modules\Quote\Models\QuotationLink;
use Modules\Services\Models\Service;
use Modules\Tasks\Models\ClientTask;
use Modules\Templates\Models\ClientSection;
use Modules\Templates\Models\Template;
use Modules\Templates\Models\TemplateClient;


class Client extends Model
{
    use LogsActivity, Notifiable,SoftDeletes;
    protected static $logAttributes =[  'company_name',
    'contact_person',
    'phone_number',
    'email',
    'address',
    'additional_questions',
    'number_of_employees',
    'status',
    'progress'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }


    protected $fillable = [
        'company_name',
        'contact_person',
        'phone_number',
        'email',
        'address',
        'additional_questions',
        'number_of_employees',
        'status',
        'progress',
        'industry'
    ];

    protected $casts = [
        'additional_questions' => 'array'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function questions()
    {
        return $this->hasMany(ClientQuestion::class);
    }

    public function template_client()
    {
        return $this->hasMany(TemplateClient::class, 'client_id');
    }

      /**
     * @return BelongsToMany
     */
    public function templates()
    {
        return $this->belongsToMany(Template::class, 'template_clients')->withTrashed();
    }

    public function template(){
        return $this->hasManyThrough(
            Template::class,
            TemplateClient::class,
        );
    }



    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }

    public function MRRServices()
    {
        return $this->belongsToMany(Service::class,'client_MRR_services')->withPivot(['cost','qty','total_amount']);
    }

    public function ORRServices()
    {
        return $this->belongsToMany(Service::class,'client_ORR_services')->withPivot(['cost','qty','total_amount']);
    }


    public function scopeSearchCompanyName($query, $name){
        $check = $name && $name != "";
        $query->when($check, function($query) use ($name){
            $query->where(function ($query) use ($name){
                $query->where('contact_person','LIKE', '%'.$name.'%');
            });
        });
    }

    public function sections()
    {
        return $this->hasMany(ClientSection::class)->withTrashed();
    }

    public function tasks()
    {
        return $this->hasMany(ClientTask::class);
    }

    public function quoteLinks()
    {
        return $this->hasMany(QuotationLink::class);
    }







}
