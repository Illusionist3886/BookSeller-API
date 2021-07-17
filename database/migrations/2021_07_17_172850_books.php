<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Books extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->char('name',100);
            $table->char('image',100)->nullable();
            $table->string('writer')->nullable();
            $table->string('publisher')->nullable();
            $table->text('description')->nullable();
            $table->double('unit_price',10,2);
            $table->double('offer_price',10,2)->nullable();
            $table->double('discount',10,2)->nullable();
            $table->date('promotion_validity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
