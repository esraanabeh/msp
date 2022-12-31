<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Organization\Models\Organization;
use Spatie\Activitylog\Traits\LogsActivity;
use Modules\Client\Models\Client;
use Modules\Opportunity\Models\Opportunity;

class Service extends Model
{
    use HasFactory ,LogsActivity, SoftDeletes;
    protected static $logAttributes =['title','unit_cost'];

    protected $fillable = [
        'title',
        'unit_cost',
        'organization_id',
        'type'

        ];


        public function organization()
        {
            return  $this->belongsTo(Organization::class, 'organization_id', 'id');
        }

        public function opportunities()
        {
            return $this->hasMany(Opportunity::class);
        }
        public function ClientMRRServices()
        {
            return $this->belongsToMany(Client::class,'client_MRR_services')->withPivot(["id","qty","cost","total_amount"]);
        }

        public function ClientORRServices()
        {
            return $this->belongsToMany(Client::class,'client_ORR_services')->withPivot(["id","qty","cost","total_amount"]);
        }
}
