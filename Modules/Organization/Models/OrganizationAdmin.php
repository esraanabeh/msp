<?php

namespace Modules\Organization\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;

class OrganizationAdmin extends Model
{
    use LogsActivity;
    protected $table = "organization_admins";
    protected $fillable = [
        'user_id',
        'organization_id'
    ];

    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function organization()
    {
        return  $this->belongsTo(Organization::class, 'organization_id', 'id');
    }
}



