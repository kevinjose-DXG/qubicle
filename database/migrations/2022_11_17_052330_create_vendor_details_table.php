<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_details', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->string('reg_business_name');
            $table->integer('shop_category');
            $table->integer('business_category');
            $table->string('shop_name');
            $table->string('gst_no');
            $table->string('mobile_no')->nullable();
            $table->string('email_id')->nullable();
            $table->string('address1');
            $table->string('address2');
            $table->integer('district');
            $table->integer('state');
            $table->integer('location');
            $table->string('pin');
            $table->integer('plan_id')->default(0);
            $table->text('business_logo')->nullable();
            $table->enum('plan_payment_status',['paid','pending','notpaid'])->default('notpaid');
            $table->enum('vendor_plan_activated',['0','1'])->default('0');
            $table->enum('admin_approved_vendor',['0','1'])->default('0');
            $table->enum('admin_approved_vendor_payment',['0','1'])->default('0');
            $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('vendor_details');
    }
}
