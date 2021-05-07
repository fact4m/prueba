<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use UsesTenantConnection;

    public $timestamps = false;

    protected $fillable = [
        'delete_document_demo',
    ];
}