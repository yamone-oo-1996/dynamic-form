<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataTransferTracking extends Model
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
    protected $table = 'data_transfer_tracking';

    public $timestamps = false;
    protected $fillable = [
        'reference_id',
        'service_reference_id',
        'data',
        'is_processed',
        'status'
    ];
}
