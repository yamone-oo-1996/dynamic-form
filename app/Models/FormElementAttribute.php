<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormElementAttribute extends Model
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
    protected $table = 'form_element_attribute';

    public $timestamps = false;
    protected $fillable = [
        'form_element_id',
        'rule_type_id',
        'rule_value',
        'status'
    ];
}
