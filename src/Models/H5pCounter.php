<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PCounter extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'h5p_counters';

    protected $fillable = [
        'type',
        'library_name',
        'library_version',
        'num',
    ];
}
