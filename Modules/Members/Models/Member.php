<?php

namespace Modules\Members\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Team\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Member extends Model
{
    use HasFactory , LogsActivity;
    protected static $logAttributes =['first_name','last_name','email','name','team_id'];
    
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [

        'user_id',
        'team_id',
        'role'
        ];



        public function user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }

        public function team(){
            return $this->belongsTo(Team::class);
        }


}
