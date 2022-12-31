<?php

namespace Modules\Templates\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Models\Client;
use Spatie\Activitylog\Traits\LogsActivity;

class TemplateClient extends Model
{
    use HasFactory, LogsActivity;
    protected static $logAttributes =['template_id','client_id'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = ['template_id','client_id'];

    public function template()
    {
        return  $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function client()
    {
        return  $this->belongsTo(Client::class, 'client_id', 'id');
    }


}
