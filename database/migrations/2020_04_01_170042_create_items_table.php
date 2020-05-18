<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('items', function (Blueprint $table){
            $table->increments('id');
            $table->string('description');
            $table->integer('amount');
            $table->integer('maxprice');
            //Foreign Key
            $table -> integer ( 'shoppinglist_id' )-> unsigned ();
            $table -> foreign ( 'shoppinglist_id')
                -> references ( 'id' )-> on ( 'shoppinglists')
                -> onDelete ( 'cascade' );
            $table -> timestamps ();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
