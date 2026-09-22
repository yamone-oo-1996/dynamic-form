<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormElement extends Model
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
    protected $table = 'form_element';

    public $timestamps = false;
    protected $fillable = [
        'en_name',
        'mm_name',
        'attribute_name',
        'element_type_id',
        'form_type_id',
        'form_group_id',
        'status'
    ];
}
