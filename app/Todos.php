<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todos extends Model
{
    //
    protected $table = 'todos';

    protected $fillable = [
        'user_id', 'title', 'description',
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
