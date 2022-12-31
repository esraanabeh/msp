<?php

namespace Modules\Templates\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class SectionEmailTemplate extends Model
{
    use HasFactory,LogsActivity;
    protected static $logAttributes =[ 'name',
        'title',
        'content',
        'section_id'
        ];

    protected $fillable = [
       'title',
       'content',
       'section_id'
        ];


    public function section()
    {
        return $this->belongsTo(Section::class,'section_id');
    }
}
