<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceData extends Model
{
    /**
     * The database connection used by the model.
     *
     * @var string
     */
    protected $connection = 'frnt_dynamic_form';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'service_data';

    public $timestamps = false;
    protected $fillable = [
        'reference_id',
        'service_reference_id',
        'service_type',
        'data',
        'status'
    ];
}
