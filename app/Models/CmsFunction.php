<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsFunction extends Model
{
    //
    protected $table = 'tb_function';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];
}
