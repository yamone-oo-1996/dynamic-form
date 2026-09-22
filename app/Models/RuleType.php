<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleType extends Model
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
    protected $table = 'rule_type';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];
}
