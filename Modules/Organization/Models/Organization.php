<?php

namespace Modules\Organization\Models;
use Illuminate\Database\Eloquent\Model;
use Modules\Quote\Models\QuoteTemplate;
use Modules\Services\Models\MasterServiceAgreement;
use Modules\Services\Models\Service;
use Modules\Templates\Models\Template;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\Activitylog\Traits\LogsActivity;

class Organization extends Model implements HasMedia
{

    use  InteractsWithMedia ,LogsActivity;
    protected static $logAttributes =['name',
    'team_members',
    'main_speciality',
    // 'usage_system_target',
    'phone_number',
    'website_url',
    'terms_and_conditions',
    'email',
    'status'];

    protected $fillable = [
        'name',
        'team_members',
        'main_speciality',
        // 'usage_system_target',
        'phone_number',
        'website_url',
        'terms_and_conditions',
        'email',
        'status'
        ];


        public function getCoverAttribute(): string
        {
            return $this->getFirstMediaUrl('logo')  ;
        }

        public function getImageAttribute($value)
        {
            if($value) return url($value);

            return $value;
        }

        public function service()
        {
            return $this->hasMany(Service::class, 'organization_id');
        }

        public function masterServiceAgreement()
        {
            return $this->hasMany(MasterServiceAgreement::class, 'organization_id');
        }


        public function organization_admin()
        {
            return $this->hasMany(OrganizationAdmin::class, 'organization_id');
        }

        public function industry()
        {
            return $this->hasMany(Industry::class, 'orgnization_id');
        }

        public function Template()
        {
            return $this->hasMany(Template::class, 'organization_id');
        }

        public function quoteTemplate()
        {
            return $this->hasOne(QuoteTemplate::class);
        }


}
