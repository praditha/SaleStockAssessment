<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->integer('order_id')->unsigned();
            $table->integer('logistic_partner_id')->unsigned()->nullable();
            $table->string('recipient_name');
            $table->string('recipient_email');
            $table->string('recipient_phone_no');
            $table->string('recipient_address');
            $table->enum('status', ['packing', 'on the way', 'delivered'])->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('logistic_partner_id')->references('id')->on('logistic_partners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}
