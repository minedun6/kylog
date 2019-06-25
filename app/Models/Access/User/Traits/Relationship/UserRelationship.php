<?php

namespace App\Models\Access\User\Traits\Relationship;

use App\Models\Access\User\SocialLogin;
use App\Models\Client\Client;
use App\Models\Company\Company;
use App\Models\Supplier\Supplier;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(config('access.role'), config('access.role_user_table'), 'user_id', 'role_id');
    }

    /**
     * @return mixed
     */
    public function providers()
    {
        return $this->hasMany(SocialLogin::class);
    }

    /**
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return mixed
     */
    public function supplier()
    {
        return $this->belongsTo(Company::class, 'company_id')->where('type', 1);
    }

    /**
     * @return mixed
     */
    public function client()
    {
        return $this->belongsTo(Company::class, 'company_id')->where('type', 2);
    }

}
