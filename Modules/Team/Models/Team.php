<?php

namespace Modules\Team\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Members\Models\Member;
use Modules\Templates\Models\Section;
use Modules\Organization\Models\Organization;
use Spatie\Activitylog\Traits\LogsActivity;

class Team extends Model
{
    use HasFactory , LogsActivity;
    protected static $logAttributes =['name'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'name',
        'status',
        'organization_id'
        ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function active_members()
    {
        return $this->hasMany(Member::class)->whereHas('user',function($q){
            $q->where('status',true);
        });
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function section()
    {
        return $this->hasMany(Section::class)->withTrashed();
    }
}
