<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = ['commenttext'];

    public function shoppinglist():BelongsTo
    {
        return $this ->belongsTo(Shoppinglist::class );
    }
    public function user():BelongsTo
    {
        return $this->belongsTo( User::class);
    }
}
