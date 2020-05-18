<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppinglistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoppinglists', function (Blueprint $table) {

            $table->increments('id');
            $table->string('title');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
                        $table->string('costs')->nullable();
            $table->string('deadline')->nullable();
            $table->integer('helper_id')->unsigned()->nullable();
            $table->foreign('helper_id')
                ->references('id')->on('users');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shoppinglists');
    }
}
