<?php

namespace Modules\Templates\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembersTemplate extends Model
{
    use HasFactory;
    protected $fillable = [

        'template_id',
        'member_id',

        ];


        public function member()
        {
            return  $this->belongsTo(Member::class, 'member_id', 'id');
        }

        public function template()
        {
            return  $this->belongsTo(Template::class, 'template_id', 'id');
        }

}
