<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;

class H5PContentsUserData extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'h5p_contents_user_data';

    protected $fillable = [
        'content_id',
        'user_id',
        'sub_content_id',
        'data_id',
        'data',
        'preload',
        'invalidate',
        'updated_at',
    ];
}
