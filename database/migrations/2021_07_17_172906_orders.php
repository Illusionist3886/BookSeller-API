<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user');
            $table->string('books'); // Array of books and quantity
            $table->string('coupon')->nullable();
            $table->integer('total',10,2);
            $table->integer('discount',10,2);
            $table->integer('net',10,2);
            $table->string('payment')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('trx_id')->nullable();
            $table->string('payment_note')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
