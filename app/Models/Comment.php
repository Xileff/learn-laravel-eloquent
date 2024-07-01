<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true; // defaultnya mmg true

    protected $attributes = [
        'title' => 'Sample Title',
        'comment' => 'Sample Comment'
    ];
}
