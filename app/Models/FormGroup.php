<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormGroup extends Model
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
    protected $table = 'form_group';

    public $timestamps = false;
    protected $fillable = [
        'en_name',
        'mm_name',
        'attribute_name',
        'form_type_id',
        'parent_id',
        'status'
    ];
}
