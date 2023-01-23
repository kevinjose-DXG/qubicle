<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->integer('plan_id');
            $table->string('order_id')->nullable();
            $table->date('real_start_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('real_end_date')->nullable();
            $table->enum('plan_status',['active','inactive','expired'])->default('inactive');
            $table->enum('payment_status',['paid','notpaid'])->default('notpaid');
            $table->enum('vendor_plan_status',['active','inactive'])->default('inactive');
            $table->enum('payment_approval',['pending','active','inactive'])->default('inactive');
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
        Schema::dropIfExists('vendor_plans');
    }
}
