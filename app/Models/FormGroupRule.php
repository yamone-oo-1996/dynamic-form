<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormGroupRule extends Model
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
    protected $table = 'form_group_rule';

    public $timestamps = false;
    protected $fillable = [
        'form_group_id',
        'rule_type_id',
        'rule_value',
        'status'
    ];
}
