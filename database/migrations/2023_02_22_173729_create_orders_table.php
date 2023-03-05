<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('order_no')->nullable();
            $table->date('order_date')->nullable();
            $table->enum('order_status',['onprocess','confirmed','shipped','delivered','cancelled'])->default('onprocess');
            $table->enum('payment_status',['paid','notpaid','cancelled','refunded'])->default('paid');
            $table->float('sub_total',8,2)->default('0.00');
            $table->float('delivery_charge',8,2)->default('0.00');
            $table->float('discount_amount',8,2)->default('0.00');
            $table->float('tax',8,2)->default('0.00');
            $table->float('grand_total',8,2)->default('0.00');
            $table->date('delivery_date')->nullable();
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
