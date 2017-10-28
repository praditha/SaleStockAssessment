<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('coupon_id')->unsigned()->nullable();
            $table->double('total_price', 12, 2);
            $table->enum('status', ['ordered', 'paid', 'shipped']);
            $table->string('recipient_name');
            $table->string('recipient_email');
            $table->string('recipient_phone_no');
            $table->string('recipient_address');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('coupon_id')->references('id')->on('coupons');
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
