<?php

namespace LaraH5P\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;

class H5PContent extends Model
{
    protected $table = 'h5p_contents';

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'title',
        'library_id',
        'parameters',
        'filtered',
        'slug',
        'embed_type',
        'disable',
        'content_type',
        'author',
        'source',
        'year_from',
        'year_to',
        'license',
        'license_version',
        'license_extras',
        'author_comments',
        'changes',
        'default_language',
        'keywords',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUser()
    {
        return DB::table('users')->where('id', $this->user_id)->first();
    }
}
