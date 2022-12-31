<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Templates\Models\Section;
use Modules\Members\Models\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model implements HasMedia
{
    use HasFactory,LogsActivity, InteractsWithMedia,SoftDeletes;

    protected static $logAttributes =['title',
    'options',
    'type',
    'description',
    'is_required',
    'section_id',
    'status',
    'done',
    'description'];

    protected $fillable = [
        'title',
        'options',
        'type',
        'is_required',
        'section_id',
        'status',
        'done',
        'description',
        'display_order'
        ];

        protected $casts =['options' =>'array'];




        public function section()
        {
            return  $this->belongsTo(Section::class, 'section_id', 'id')->withTrashed();
        }

        public function getImageAttribute($value)
        {
            if($value) return url($value);

            return $value;
        }

        public function client_task()
        {
            return $this->hasMany(ClientTask::class , 'task_id');
        }



        public function getFileAttribute()
        {
            return $this->getFirstMedia('file');
        }


    /**
         * @var string[]
         */
        protected $appends= [
            'file'
        ];
        // public function member()
        // {
        //     return  $this->belongsTo(Member::class, 'member_id', 'id');
        // }

       



}
