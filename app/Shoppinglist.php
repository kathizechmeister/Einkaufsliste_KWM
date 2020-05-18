<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shoppinglist extends Model
{
    /**
     * welche Attribute z.B. über HTTP Request oder auch über Array Parameter gesetzt werden dürfen.
     */
    protected $fillable = [ 'title' ,'deadline' , 'costs' ,'user_id','helper_id', /*'comments', /*'items'/*'helper_id', 'user', 'helper'*/];

    public function user():BelongsTo
    {
        return $this->belongsTo( User::class);
    }

    public function helper():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function items():HasMany
    {
        return $this->hasMany(Item::class );
    }


    public function comments():HasMany
    {
        return $this->hasMany(Comment::class );
    }

}
