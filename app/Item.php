<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Item extends Model
{
    protected $fillable = ['description' , 'amount', 'maxprice'];

    public function shoppinglist():BelongsTo
    {
        return $this ->belongsTo(Shoppinglist::class );
    }
}
