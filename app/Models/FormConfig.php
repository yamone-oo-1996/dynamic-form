<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormConfig extends Model
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
    protected $table = 'form_config';

    public $timestamps = false;
    protected $fillable = [
        'form_type_id',
        'attribute_name',
        'value',
        'status',
    ];
}
