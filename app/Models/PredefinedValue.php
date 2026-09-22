<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PredefinedValue extends Model
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
    protected $table = 'predefined_value';

    public $timestamps = false;

    protected $fillable = [
        'form_element_id',
        'en_name',
        'mm_name',
        'value',
        'order',
        'status',
    ];
}
